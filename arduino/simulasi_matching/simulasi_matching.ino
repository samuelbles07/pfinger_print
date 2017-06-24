/*
  SEMUA YANG BERHUBUNGAN DENGAN SOFTWARE SERIAL DAN mySerial HANYA UNTUK
  MENAMPILKAN LEWAT SERIAL KE PUTTY BIAR LEBIH LELUASA LIAT HASIL NYA
  KALAU DARI LCD KURANG BESAR
  JADI NANTI ITU SEMUA DITUKAR DENGAN LCD
*/

#include <SPI.h>        // bagian dari library ethernet
#include <Ethernet.h>
#include <SoftwareSerial.h>

#define id_sensor 2

SoftwareSerial mySerial(6, 7); // RX, TX

byte mac[] = {0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};  // memberikan mac address acak ke arduino
IPAddress ip(192, 168, 1, 20); // memberikan ip acak ke arduino

IPAddress server(192, 168, 43, 170); // ip server yang mau dikoneksikan

EthernetClient client;  // menginisialisasikan library ethernet dan menampung object ke client



#define fstatus digitalRead(2)
#define button digitalRead(3)

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
  if (client.connect(server, 8888)) {
    mySerial.print("connected");
  }
  else {
    // if you didn't get a connection to the server:
    mySerial.print("connection fail");
    while (1);
  }
  mySerial.println("put your finger..");
}

void loop() {
  // put your main code here, to run repeatedly:
  if (fstatus == HIGH) {    // jika ada yang meletakkan jari ke sensor
    matching();   // maka masuk fungsi mathcing
  }

  if (!client.connected()) {        // jika putus koneksi
    mySerial.println("disconnect");
    client.stop();
    // do nothing:
    while (true);   // berhenti nunggu di restart
  }
}

void matching() {
  delay(500);   // tunda agar posisi jari benar dulu
  // matching command
  byte matching[] = {0xF5, 0x0C, 0x00, 0x00, 0x00, 0x00, 0x0C, 0xF5};
  byte ret[8];
  int i = 0, j = 0;
  mySerial.println("matching..");
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
    mySerial.println(ret[x], HEX);
    delay(5);
  }

  if(ret[5] == 0){
    mySerial.println("not match..");
    return 0;
  }
  // mode, userId high, userId low, id_sensor
  int data[] = {2, ret[3], ret[4], id_sensor};
  for (int x = 0; x < 4; x++) {   // kirim data
    client.print(data[x]);
    delayMicroseconds(100);
  }
  mySerial.println("data sent");
  delay(2000);
}

