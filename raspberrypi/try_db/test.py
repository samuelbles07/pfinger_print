import MySQLdb as mdb
import sys

def readData(con):
  with con:

    cur = con.cursor(mdb.cursors.DictCursor)
    cur.execute("SELECT * FROM admin")

    rows = cur.fetchall()

    for row in rows:
      print row["nip"], row["username"], row["password"]


def updateData(con):
  with con:

    cur = con.cursor()
    cur.execute("UPDATE admin SET username = %s, password = %s WHERE nip = %s", ("admin","admin", "121402105"))
    print "updated: ", cur.rowcount


def insertData(con):
  with con:

    cur = con.cursor()
    cur.execute("INSERT INTO admin(`nip`, `username`, `password`) VALUES (%s,%s,%s)",("12345678", "tes", "tes"))
    print "inserted: ", cur.rowcount


try:
  con = mdb.connect('localhost', 'root', 'raspberry', 'sidikjari')
  
  cur = con.cursor()
  #cur.execute("SELECT VERSION()")  
  #ver = cur.fetchone()
  #print "databse version : %s" % ver
  insertData(con)
  readData(con)
  #updateData(con)


except mdb.Error, e:

  print "Error %d: %s" % (e.args[0], e.args[1])
  #print "err"
  sys.exit(1)

finally:

  if con:
    con.close()
