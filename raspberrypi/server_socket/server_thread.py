import socket
import select
import threading
import struct
import sys
import datetime
import MySQLdb as mdb


_HOST = '192.168.1.10'  # defines the host as "localhost"
_PORT = 10004       # defines the port as "10000"

class Server(threading.Thread):
    """
    Defines the server as a Thread.
    """

    MAX_WAITING_CONNECTIONS = 10
    RECV_BUFFER = 3

    def __init__(self, host, port):
        """
        Initializes the server.

        :param host: the host on which the server is bounded
        :param port: the port on which the server is bounded
        """
        threading.Thread.__init__(self)
        self.host = host
        self.port = port
        self.connections = []  # collects all the incoming connections
        self.running = True  # tells whether the server should run


    def _bind_socket(self):
        """
        Creates the server socket and binds it to the given host and port.
        """
        self.server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        self.server_socket.bind((self.host, self.port))
        self.server_socket.listen(self.MAX_WAITING_CONNECTIONS)
        self.connections.append(self.server_socket)

    
    def _send(self, sock, msg):
        # Sends the packed message
        print "kirim"
        msgLen = len(msg)
        # print msgLen
        for index in range(0, msgLen):
            # print index
            sock.send(msg[index] + ',')


    def _receive(self, sock):
        """
        Receives an incoming message from the client and unpacks it.

        :param sock: the incoming socket
        :return: the unpacked message
        """
        data = ''
        incoming = []
        count = 0
        while count < 2:
            data = sock.recv(self.RECV_BUFFER)
            # print data
            incoming.append(data)
            if data == '245':
                # print count
                count += 1

        print 'habis'
        return incoming

    def _broadcast(self, client_socket, client_message):
        """
        Broadcasts a message to all the clients different from both the server itself and
        the client sending the message.

        :param client_socket: the socket of the client sending the message
        :param client_message: the message to broadcast
        """
        for sock in self.connections:
            not_server = sock != self.server_socket
            currentClient = sock != client_socket
            if not_server: #and currentClient:
                try:
                    self._send(sock, client_message)
                except socket.error:
                    # Handles a possible disconnection of the client "sock" by...
                    print '1 closed'
                    sock.close()  # closing the socket connection
                    self.connections.remove(sock)  # removing the socket from the active connections list


    def _saveData(self, table, user_id, data):
        print "saving"
        print user_id
        print data
        try:
            con = mdb.connect('127.0.0.1', 'root', '', 'sidikjari')
            cur = con.cursor()
            if table == "data_anggota":
                with con:
                    # cur = con.cursor()
                    cur.execute("INSERT INTO data_anggota(`data_sidik`, `id_high`, `id_low`) \
                                VALUES (%s, %s, %s)", \
                                (data, user_id[0], user_id[1]))
                    print "inserted: ", cur.rowcount
                    
            elif table == "log_anggota":
                now = datetime.datetime.now()
                jam8 = now.replace(hour=8, minute=0, second=0, microsecond=0)
                jam5 = now.replace(hour=17, minute=0, second=0, microsecond=0)
                keterangan = 'A'
                print keterangan
                if now >= jam8 and now <= jam5:
                    keterangan = 'H'
                with con:
                    # cur = con.cursor()
                    cur.execute("INSERT INTO log_anggota(`id_high`, `id_low`, `id_hari`, `id_sensor`, `tgl`, `jam`, `keterangan`) \
                                VALUES (%s,%s,%s,%s,%s,%s,%s)",\
                                (user_id[0], user_id[1], now.isoweekday(), data, now.date(), now.time(), keterangan))
                    print "inserted: ", cur.rowcount

        except mdb.Error, e:
            print "Error %d: %s" % (e.args[0], e.args[1])
            sys.exit(1)

        finally:
            if con:
                con.close()


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
        joinData = " ".join(data[1:])
        self._broadcast(client_socket, data[1:])
        self._saveData('data_anggota', data[2:4], joinData)

    def _run(self):
        """
        Actually runs the server.
        """
        while self.running:
            # Gets the list of sockets which are ready to be read through select non-blocking calls
            # The select has a timeout of 60 seconds
            try:
                ready_to_read, ready_to_write, in_error = select.select(self.connections, [], [], 60)
            except socket.error:
                continue
            else:
                for sock in ready_to_read:
                    # If the socket instance is the server socket...
                    if sock == self.server_socket:
                        try:
                            # Handles a new client connection
                            client_socket, client_address = self.server_socket.accept()
                        except socket.error:
                            break
                        else:
                            self.connections.append(client_socket)
                            print "Client (%s, %s) connected" % client_address

                    # ...else is an incoming client socket connection
                    else:
                        try:
                            data = self._receive(sock) # Gets the client message...
                            if data:
                                # ... and broadcasts it to all the connected clients
                                # print data
                                if data[0] == '1':
                                    self._regis(sock, data)
                                elif data[0] == '2':
                                    self._match(data)
                                # self._broadcast(sock, "\r" + '<' + str(sock.getpeername()) + '> ' + data)
                        except socket.error:
                            # Broadcasts all the connected clients that a clients has left
                            print "Client (%s, %s) is offline" % client_address
                            sock.close()
                            self.connections.remove(sock)
                            continue
        # Clears the socket connection
        self.stop()

    def run(self):
        """Given a host and a port, binds the socket and runs the server.
        """
        self._bind_socket()
        self._run()

    def stop(self):
        """
        Stops the server by setting the "running" flag before closing
        the socket connection.
        """
        self.running = False
        self.server_socket.close()


def main():
    """
    The main function of the program. It creates and runs a new Server.
    """
    chat_server = Server(_HOST, _PORT)
    chat_server.start()


if __name__ == '__main__':
    """The entry point of the program. It simply calls the main function.
    """
    main()