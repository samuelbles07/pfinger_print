/*
  SEMUA YANG BERHUBUNGAN DENGAN SOFTWARE SERIAL DAN mySerial HANYA UNTUK
  MENAMPILKAN LEWAT SERIAL KE PUTTY BIAR LEBIH LELUASA LIAT HASIL NYA
  KALAU DARI LCD KURANG BESAR
  JADI NANTI ITU SEMUA DITUKAR DENGAN LCD
*/

#include <SPI.h>        // bagian dari library ethernet
#include <Ethernet.h>
#include <SoftwareSerial.h>

SoftwareSerial mySerial(7, 6); // RX, TX

byte mac[] = {0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};  // memberikan mac address acak ke arduino
IPAddress ip(192, 168, 1, 20); // memberikan ip acak ke arduino

IPAddress server(192, 168, 1, 10); // ip server yang mau dikoneksikan

EthernetClient client;  // menginisialisasikan library ethernet dan menampung object ke client

#define fstatus digitalRead(2)
#define button digitalRead(3)

int first = 0, last = 0;    // deklarasi variabel
// first byte ke 2, last byte ke 3
void setup() {
  // put your setup code here, to run once:
  Ethernet.begin(mac, ip);  // memulai ethernet dan memberikan mac dan ip
  pinMode(2, INPUT); // mendefinisikan pin 2 untuk inputan fingerprint
  pinMode(3, INPUT);  // mendefinisikan pin 3 untuk button
  Serial.begin(115200); // mendefinisikan baud rate serial sensor
  delay(100);
  mySerial.begin(9600);
  delay(100);
  mySerial.print("connecting...");  // menampilkan
  // jika arduino connect ke ip server dan port tersebut maka berhasil koneksi dengan server
  if (client.connect(server, 10006)) {
    mySerial.print("connected");
  }
  else {
    // if you didn't get a connection to the server:
    mySerial.print("connection fail");
    while (1);
  }
  mySerial.println("push the button to register..");
}

void loop() {
  // put your main code here, to run repeatedly:
  if (button == HIGH) {                   // jika button regis ditekan
    mySerial.println("registering..");
    regis();                              // eksekusi fungsi regis
    mySerial.println("done");
    // KIRIM DATA
    delay(10000);                 // tunda (kondisional lama tundanya)
  }
  if (!client.connected()) {        // jika putus koneksi
    mySerial.println("disconnect");
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
  mySerial.println("put your finger..");
  while (fstatus == LOW) delay(5);  // menunggu sampai jari diletakkan
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
  mySerial.print("i: "); mySerial.println(i);
  mySerial.print("j: "); mySerial.println(j);
  if (j == 30) {                                // jika 30 maka timeout returnya
    mySerial.println("timeout, please reset");
    while (1);
  }
  else if (myArr[4] != 0 || myArr[12] != 0 || myArr[20] != 0) {  // jika salah satu dari masing2 3 packet data
    mySerial.println("failed registering..");               // di byte ke 4 tidak sama dengan 0 maka register gagal
    return 0;                                           // langsung kembalikan nilai 0 atau selesaikan fungsi
  }
  else {
    for (int x = 0; x <= i; x++) {
      mySerial.println(myArr[x], HEX);
      delay(5);
    }
    first = myArr[18]; last = myArr[19];                    // mendapatkan user id yang terdaftar
    mySerial.print("first: "); mySerial.println(first);
    mySerial.print("last: "); mySerial.println(last);
  }

  // ***************************** GET FEATURE*********

  i = 0; j = 0;
  chk = getChk(49, 0);                                    // mendapatkan chk dengan opsi 49 dan role 0
  byte getFet[] = {245, 49, first, last, 0, 0, chk, 245}; // command get feature finger print dari user id yang barusan didaftarkan tadi
  mySerial.println("getting feature..");
  delay(100);
  Serial.write(getFet, 8);                // kirim command get feature
  while (i < 508 && j <= 25000) {         // mengharapkan 508 data feature jika sampai 25000 perulangan maka gagal
    if (Serial.available() > 0) {
      myArr[i] = Serial.read();
      i++;
    }
    j++;
  }
  mySerial.print("i: "); mySerial.println(i);
  mySerial.print("j: "); mySerial.println(j);
  for (int x = 0; x < i; x++) {
    mySerial.println(myArr[x], HEX);
    delay(5);
  }

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
  if (data[4] != 0) {
    mySerial.println("failed delete..");
    return 0;
  }
  mySerial.println("success delete");

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ DISINI KIRIM DATA KE SERVER @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  client.print(1);      // KIRIM MODE REGISTER DI AWAL DATA YAITU 1
  delayMicroseconds(1);
  for (i = 8; i < 508; i++) {   // KIRIM DATA NYA FEATURE
    client.print(myArr[i]);
    delayMicroseconds(1);
  }
  mySerial.println("selesai kirim");
}


void regisFromServer() {
  int i = 0, j = 0;
  byte myArr[500];
  // DISINI HANDLE KONEKSI DATA DARI SERVER MASUK KE ARRAY
  while (i < 500 && j < 25000) {  // HANDLE DATA SEBANYAK 500 BYTE YANG HARUS DITERIMA
    if (client.available()) {
      myArr[i] = client.read(); // TAMPUNG KE ARRAY myArr
      i++;
    }
    j++;
  }
  mySerial.print("i: "); mySerial.println(i);
  mySerial.print("j: "); mySerial.println(j);
  
  // #################################### REGIS KEMBALI DARI DATA YANG DIKIRIM DARI SERVER ###############

  byte regisBack[] = {0xF5, 0x41, 0x01, 0xF1, 0x00, 0x00, 0xB1, 0xF5};    // Command upload register
  byte data[8];
  i = 0; j = 0;
  mySerial.println("sending the data to register");
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
  for (int x = 0; x <= i; x++) {
    mySerial.println(data[x], HEX);
    delay(5);
  }
  mySerial.println("selesai ");
}


