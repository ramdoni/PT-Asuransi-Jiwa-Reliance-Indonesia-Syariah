import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="syariah"
)

table = mydb.cursor(buffered=True)

table.execute("SELECT nama FROM kepesertaan WHERE is_temp=1 limit 0,1")

kepesertaan = table.fetchall()

for item in kepesertaan:

    #find double data
    tableFind = mydb.cursor()
    tableFind.execute("SELECT nama FROM kepesertaan WHERE is_temp=1 AND (status_polis=%s OR status_polis=%s AND tanggal_lahir=%s) AND nama=%s",('Inforce','Akseptasi',item[0]))
    rowDouble = tableFind.fetchone()
    if rowDouble:
        print("%s \n",rowDouble)

    # find rate
    tableFindRate = mydb.cursor()
    tableFindRate.execute("SELECT * FROM rate WHERE tahun",())

    print(item)
