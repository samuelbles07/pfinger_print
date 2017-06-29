#include <SPI.h>        // bagian dari library ethernet
#include <Ethernet.h>
#include <LiquidCrystal.h>  //deklarasi header lcd 

byte mac[] = {0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};  // memberikan mac address acak ke arduino
IPAddress ip(192, 168, 1, 20); // memberikan ip acak ke arduino

IPAddress server(192, 168, 1, 10); // ip server yang mau dikoneksikan

EthernetClient client;  // menginisialisasikan library ethernet dan menampung object ke client
LiquidCrystal lcd(9, 8, 7, 6, 5, 4); // menginisialisasikan library lcd dan menampun object ke lcd dan memberikan definisikan pin yang kita gunakan
//RS E D4 D5 D6 D7

#define fstatus digitalRead(2)  // define pin untuk status finger print disentuh
#define button digitalRead(3)   // define pin untuk button ditekan
#define id_sensor 2     // define pin sensor no berapa
int first = 0, last = 0;    // deklarasi variabel untuk id high low

// first byte ke 2, last byte ke 3
void setup() {
  // put your setup code here, to run once:
  Ethernet.begin(mac, ip);  // memulai ethernet dan memberikan mac dan ip
  lcd.begin(16, 2); // memulai lcd dan memberika besar dari lcd x , y
  pinMode(2, INPUT); // mendefinisikan pin 2 untuk inputan fingerprint
  pinMode(3, INPUT);  // mendefinisikan pin 3 untuk button
  Serial.begin(115200); // mendefinisikan baud rate serial sensor
  delay(100);
  lcd.print("connecting...");  // menampilkan
  // jika arduino connect ke ip server dan port tersebut maka berhasil koneksi dengan server
  if (client.connect(server, 10004)) {
    lcd.clear();
    lcd.print("connected");
    lcd.setCursor(0, 1); // mendefinisikan posisi y menjadi 1
    lcd.print("put your finger");
  }
  else {
    // if you didn't get a connection to the server:
    lcd.setCursor(0, 1);
    lcd.print("connection fail");
    while (1);
  }
}

void loop() {
  // put your main code here, to run repeatedly:
  if (client.available()) {
    regisFromServer();
    lcd.clear();
    lcd.print("done receive data and regis back");
    delay(1000);
    lcd.clear();
    lcd.print("put your finger");
  }

  if (button == HIGH) {                   // jika button regis ditekan
    lcd.clear();
    lcd.print("registering..");
    regis();                              // eksekusi fungsi regis
    lcd.clear();
    lcd.print("done");
    // KIRIM DATA
    delay(1000);                 // tunda (kondisional lama tundanya)
    lcd.clear();
    lcd.print("put your finger");
  }

  if (fstatus == HIGH) {    // jika ada yang meletakkan jari ke sensor
    matching();   // maka masuk fungsi mathcing
    delay(1000);                 // tunda (kondisional lama tundanya)
    lcd.clear();
    lcd.print("put your finger");
  }

  if (!client.connected()) {        // jika putus koneksi
    lcd.print("disconnect");
    client.stop();
    // do nothing:
    while (true);   // berhenti nunggu di restart
  }
}

int getChk(int opt, int role) {// getChk punya 2 parameter yaitu opsi dan role
  int chk = 0;                 // opsi byte ke 2 ; role byte ke 4
  int data[] = {opt, first, last, role};
  //  int data[4];
  // simpan masing2 nilai ke array agar dihitung checksum nya
  // sesuai di datasheet checksum itu mulai byte ke 2 sampai byte ke 5
  // tetapi byte ke 5 selalu 0 makanya sampai 4 aja di checksum
  //  data[0] = opt; data[1] = first; data[2] = last; data[3] = role;
  for (int i = 0; i < 4; i++)
    chk ^= data[i];     // checksum fingerprint menggunakan bitwise xor
  return chk;         // kembalikan hasil checksum/chk
}


void regis() {
  //----------------------------------------- REGISTER----------

  byte myArr[600];                // mendeklarasi array
  int chk = getChk(1, 1);         // mendapati nilai chk dengan opsi 1 dan role 1
  byte reg[] = {245, 1, 0, 0, 1, 0, chk, 245};// command untuk register
  int i = 0, j = 0;
  delay(100);
  lcd.clear();
  lcd.print("put your finger..");
  while (fstatus == LOW) delay(5);  // menunggu sampai jari diletakkan
  lcd.clear();
  lcd.print("okay..");
  delay(1000);                      // tunda agar posisi jari pas
  Serial.write(reg, 8);             // kirim perintah reg
  while (!Serial.available());      // menunggu sampai adanya return dari sensor
  delay(3000);                      // tunda sampai regis dan semua data return sampai ke buffer
  while (i < 24 && j <= 30) {   // 24 byte return yang diharapkan, jika sampai 30 berarti data gagal
    if (Serial.available()) {
      myArr[i] = Serial.read();   // tampung ke array
      i++;
    }
    j++;
  }
  lcd.clear();
  if (j == 30) {                                // jika 30 maka timeout returnya
    lcd.print("timeout, please reset");
    while (1);
  }
  else if (myArr[4] != 0 || myArr[12] != 0 || myArr[20] != 0) {  // jika salah satu dari masing2 3 packet data
    lcd.print("failed registering..");               // di byte ke 4 tidak sama dengan 0 maka register gagal
    return 0;                                           // langsung kembalikan nilai 0 atau selesaikan fungsi
  }
  else {
    first = myArr[18]; last = myArr[19];                    // mendapatkan user id yang terdaftar
    lcd.clear();
    lcd.print("first: "); lcd.print(first);
    lcd.setCursor(0,1);
    lcd.print("last: "); lcd.print(last);
  }

  // ***************************** GET FEATURE*********

  i = 0; j = 0;
  chk = getChk(49, 0);                                    // mendapatkan chk dengan opsi 49 dan role 0
  byte getFet[] = {245, 49, first, last, 0, 0, chk, 245}; // command get feature finger print dari user id yang barusan didaftarkan tadi
  lcd.clear();
  lcd.print("getting feature..");
  delay(100);
  Serial.write(getFet, 8);                // kirim command get feature
  while (i < 508 && j <= 25000) {         // mengharapkan 508 data feature jika sampai 25000 perulangan maka gagal
    if (Serial.available() > 0) {
      myArr[i] = Serial.read();
      i++;
    }
    j++;
  }
  lcd.clear();
  lcd.print("done getting feature..");
  delay(500);
  // =============================DELETE USER SEBELUMNYA DI REGIS===========  INI DELETE HANYA UNTUK SIMULASI BAHWA CLIENT SERVER REGISTER NYA BERHASIL

  chk = getChk(4, 0);
  i = 0; j = 0;
  byte cmd[] = {245, 4, first, last, 0, 0, chk, 245};
  byte data[8];
  delay(100);
  Serial.write(cmd, 8);
  while (!Serial.available());
  delay(3000);
  while (i < 8 && j <= 30) {
    if (Serial.available()) {
      data[i] = Serial.read();
      i++;
    }
    j++;
  }
  lcd.clear();
  if (data[4] != 0) {
    lcd.print("failed delete..");
    return 0;
  }
  
  lcd.print("success delete");

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ DISINI KIRIM DATA KE SERVER @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  client.print(1);      // KIRIM MODE REGISTER DI AWAL DATA YAITU 1
  delayMicroseconds(1);
  for (i = 8; i < 508; i++) {   // KIRIM DATA NYA FEATURE
    client.print(myArr[i]);
    delayMicroseconds(1);
  }
  lcd.clear();
  lcd.print("selesai kirim");
}


void regisFromServer() {
  lcd.clear();
  lcd.print("data masuk");
  byte myArr[500];						// variabel penampung data yang didapat dari server
  int indexArr = 0;						// variabel counter index array
  char temp[4];								// variabel penampun per character yang dikirim server. 4 yaitu batas jumlah char cth: 245,
  int count = 0;							// varuabel penghitung header data dan footer data 
  int increm = 0;							// counter jumlah character yang didapat
  while (count < 2) {					// selama belum mendapatkan 2 kali header dan footer data yaitu 245
    temp[increm] = client.read();						// tampung char yg masuk ke variabel sementara
    if (temp[increm] == ',') {							// jika mendapatkan koma maka data sudah di tumpuk
      myArr[indexArr] = atoi(temp);					// convert char array ke int
      if (myArr[indexArr] == 245) count++;	// jika itu header data atau footer maka var count di counter
      indexArr++;														// maka indexArr di counter
			lcd.clear();
      lcd.print(indexArr);									// tampilkan banyak data yg sudah ditampung (opsional)
      increm = 0;														// counter variabel increm di kembalikan ke 0 karena mau hitung ulang penyusunan data
    }
    else {
      increm++;															// jika belum koma maka var increm di counter
    }
  }
	lcd.clear();
  lcd.print(indexArr);										// tampilkan nilai akhir indexArr
	delay(500);
  // #################################### REGIS KEMBALI DARI DATA YANG DIKIRIM DARI SERVER ###############

  byte regisBack[] = {0xF5, 0x41, 0x01, 0xF1, 0x00, 0x00, 0xB1, 0xF5};    // Command upload register
  byte data[8];
  int i = 0, j = 0;
  lcd.print("sending the data to register");
  delay(100);
  Serial.write(regisBack, 8);   // kirim header command
  Serial.write(myArr, 500);     // data command
  while (!Serial.available());
  delay(3000);
  while (i < 8 && j <= 30) {
    if (Serial.available()) {
      data[i] = Serial.read();
      i++;
    }
    j++;
  }
}

void matching() {
  delay(500);   // tunda agar posisi jari benar dulu
  // matching command
  byte matching[] = {0xF5, 0x0C, 0x00, 0x00, 0x00, 0x00, 0x0C, 0xF5};
  byte ret[8];
  int i = 0, j = 0;
  lcd.print("matching..");
  delay(100);
  Serial.write(matching, 8);
  while (!Serial.available());  // menunggu sampai adanya return dari sensor
  delay(1000);                  // tunda sampai regis dan semua data return sampai ke buffer
  while (i < 8 && j <= 30) {    // 8 byte return yang diharapkan, jika sampai 30 berarti data gagal
    if (Serial.available()) {
      ret[i] = Serial.read();  // tampung return ke array
      i++;
    }
    j++;
  }
  for (int x = 0; x < i; x++) {   // ini hanya nampilkan return
    lcd.print(ret[x], HEX);
    delay(5);
  }

  if (ret[4] == 0) {                  // JIKA USER ROLE 0 MAKA NOT MATCH
    lcd.print("not match..");
    return 0;
  }
  else {
    // mode, head, user high, user low, id sensor, foot
    int id_low = ret[2];
    int id_high = ret[3];
    lcd.print(id_low);
    lcd.print(id_high);
    byte data[] = {2, 245, ret[2], ret[3], id_sensor,  245};
    for (int x = 0; x < 6; x++) {   // kirim data
      client.print(data[x]);
      delayMicroseconds(1);
    }
    lcd.print("data sent");
  }

  delay(1000);
}
