# import module
import socket
import select
import threading
import sys
import datetime
import MySQLdb as mdb


_HOST = '192.168.1.10'  # defines the host as "localhost"
_PORT = 10003       # defines the port as "10000"

class Server(threading.Thread):     #menginisialisasi class server
    """
    Mendefinisikan server sebagai thread.
    """

    MAX_WAITING_CONNECTIONS = 10    #maksimal koneksi yang konek
    RECV_BUFFER = 3             # buffer 3 byte yang diterima

    def __init__(self, host, port):     #subclass init
        """
        Initializes the server.

        :parameter host: host dimana server di inisialisasi
        :parameter port: port dimana server diinisialisais
        """
        threading.Thread.__init__(self) # memulai thread 
        self.host = host    #menyimpan variabel global ke local class
        self.port = port
        self.connections = []  # list koneksi yang datang
        self.running = True  # variabel server berjalan


    def _bind_socket(self):
        """
        bind semua konfigurasi
        """
        self.server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)  # socket tcp
        self.server_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1) # socket option
        self.server_socket.bind((self.host, self.port)) # bind host dan port
        self.server_socket.listen(self.MAX_WAITING_CONNECTIONS) # listen terhadap koneksi tersebud
        self.connections.append(self.server_socket) # tambah list koneksi baru

    
    def _send(self, sock, msg):
        # Sends the packed message
        print "kirim"
        msgLen = len(msg) # menyimpan panjang array msg
        # print msgLen
        for index in range(0, msgLen):  #loop dari 0 sampai panjang arr
            # print index
            sock.send(msg[index] + ',') # kirim per array dan akhiri dengan koma


    def _receive(self, sock):
        """
        Receives an incoming message from the client and unpacks it.

        :param sock: dari koneksi mana masuk
        """
        data = ''
        incoming = [] #deklarasi array sebagai list data yang masuk
        count = 0   # hitung header dan footer packet data
        while count < 2:
            data = sock.recv(self.RECV_BUFFER)  # tampung data sesuai buffer yang dideklarasi sebelumnya
            # print data
            incoming.append(data)   # tambahkan data ke index array yang baru
            if data == '245':   # jika jumpa packet header atau footer count + 1
                # print count
                count += 1

        print 'habis'
        return incoming # kembalian data yang didapat

    def _broadcast(self, client_socket, client_message):
        """
        Broadcasts pesan ke semua koneksi yang terhubung ke server kecuali pengirim,
        untuk simulasi pengirim juga mendapatkan broadcast

        :param client_socket: the socket of the client sending the message
        :param client_message: the message to broadcast
        """
        for sock in self.connections:                   # loop ke semua koneksi yang ada
            not_server = sock != self.server_socket     # cek apakah itu ip server sendiri
            currentClient = sock != client_socket       # cek apakah itu koneksi si pengirim
            if not_server: #and currentClient:          # jika kondisi benar, untuk yang currentClient dikomentar karna utk simulasi
                try:
                    self._send(sock, client_message)    # kirim pesan melalui sub class _send
                except socket.error:
                    # Handles a possible disconnection of the client "sock" by...
                    print '1 closed'
                    sock.close()  # closing the socket connection
                    self.connections.remove(sock)  # removing the socket from the active connections list


    def _saveData(self, table, user_id, data):
        """
            table : itu ke tabel mana di save,
            user_id: id user
            \ ini yang ada di query hanya penyambung code ke bawah, bahwa masih 1 line
            biar ga panjang ke samping aja
            konsep simpang database try, Exception, finally
            try : mencoba eksekusi code, jika gagal itu excep, jika berhasil finnaly
        """
        print "saving"
        print user_id
        print data
        try:
            con = mdb.connect('127.0.0.1', 'root', 'raspberry', 'sidikjari') # membuat koneksi ke db
            cur = con.cursor()
            if table == "data_anggota":
                with con:
                    # eksekusi query
                    cur.execute("INSERT INTO data_anggota(`data_sidik`, `id_high`, `id_low`) \
                                VALUES (%s, %s, %s)", \
                                (data, user_id[0], user_id[1]))
                    print "inserted: ", cur.rowcount
            elif table == "log_anggota":
                now = datetime.datetime.now()   # mendapatkan jam dan hari sesuai sistem
                jam8 = now.replace(hour=8, minute=0, second=0, microsecond=0)   # variabel jam 5
                jam5 = now.replace(hour=17, minute=0, second=0, microsecond=0)  # variabel jam 8
                keterangan = ''
                # untuk membandingkan jika sekarang itu diatas jam 8 dan dibawah jam 17
                if now >= jam8 and now <= jam5:
                    keterangan = 'H'    # keterangan hadir
                else:
                    keterangan = 'A'
                print keterangan
                with con:
                    # eksekusi query
                    cur.execute("INSERT INTO log_anggota(`id_high`, `id_low`, `id_hari`, `id_sensor`, `tgl`, `jam`, `keterangan`) \
                                VALUES (%s,%s,%s,%s,%s,%s,%s)",\
                                (user_id[0], user_id[1], now.isoweekday(), data, now.date(), now.time(), keterangan))
                    print "inserted: ", cur.rowcount

        except mdb.Error, e:
            print "Error %d: %s" % (e.args[0], e.args[1])
            sys.exit(1)

        finally:
            if con:
                con.close() # tutup koneksi


    def _match(self, data):
        """
            data[2] & data[3] : user id
            data[4] : id sensor
        """
        print 'match'
        self._saveData('log_anggota', data[2:4], data[4])


    def _regis(self, client_socket, data):
        """
            data[2] & data[3] : user id.
            data[1:] : data finger print, simpan di db dan broadcast.
        """
        print 'regis'
        # buat data yang mau di save ke db jadi text string yang dipisah spasi
        joinData = " ".join(data[1:])
        self._broadcast(client_socket, data[1:])
        self._saveData('data_anggota', data[2:4], joinData)

    def _run(self):
        """
        Actually runs the server.
        """
        while self.running:
            # mengambil koneksi dari list yang siap untuk di listen
            # fungsi select mempunyai timeout 60 detik
            try:
                ready_to_read, ready_to_write, in_error = select.select(self.connections, [], [], 60)
            except socket.error:
                continue
            else:
                for sock in ready_to_read:
                    # jika ada koneksi yang datang untuk server
                    if sock == self.server_socket:
                        try:
                            # terima koneksi yang masuk
                            client_socket, client_address = self.server_socket.accept()
                        except socket.error:
                            break
                        else:
                            self.connections.append(client_socket)  # tambahkan ke list
                            print "Client (%s, %s) connected" % client_address

                    # ...else untuk pesan yang datang dari client
                    else:
                        data = self._receive(sock) # ambil data dari sub class _receive
                        # jika data awal yang diterima 1 maka regis, jika 2 matching
                        if data:
                            if data[0] == '1':
                                self._regis(sock, data)
                            elif data[0] == '2':
                                self._match(data)
        # Clears the socket connection
        self.stop()

    def run(self):
        """run the server
        """
        self._bind_socket()
        self._run()

    def stop(self):
        """
        stop the server
        """
        self.running = False
        self.server_socket.close()


def main():
    """
    run server thread
    """
    server = Server(_HOST, _PORT)
    server.start()


if __name__ == '__main__':
    # call the main function
    main()
