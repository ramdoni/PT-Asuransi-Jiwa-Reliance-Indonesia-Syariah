import mysql.connector
import pusher
import sys

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="nasipadangpakerendang",
  database="ajrius"
)

pusher_client = pusher.Pusher(
  app_id='1462725',
  key='61e7a83b5c1a48939522',
  secret='9d0ca63f3c20fcc18ca9',
  cluster='ap1',
  ssl=True
)

table = mydb.cursor(buffered=True)

def calculate(polis_id,transaction_id):
  # table.execute("SELECT nama,tanggal_lahir FROM kepesertaan WHERE is_temp=1 limit 0,1")
  table.execute("SELECT kepesertaan.id,kepesertaan.nama,kepesertaan.tanggal_lahir,kepesertaan.usia,kepesertaan.masa_bulan,kepesertaan.basic FROM kepesertaan"
                +" JOIN rate table_rate ON table_rate.tahun=kepesertaan.usia AND table_rate.bulan=kepesertaan.masa_bulan AND table_rate.polis_id=kepesertaan.polis_id" 
                +" WHERE is_temp=1")
  no=0
  kepesertaan = table.fetchall()
  for item in kepesertaan:
    kepesertaan_id = item[0]
    nama = item[1]
    tanggal_lahir = item[2]
    usia = item[3] 
    masa_bulan = item[4]
    nilai_manfaat_asuransi = item[5]

    print("No : ", no," : Nama : ",nama)
    no = no+1
    if nilai_manfaat_asuransi=="" or nilai_manfaat_asuransi == 0:
      continue

    #find double data
    tableFind = mydb.cursor()
    tableFind.execute("SELECT nama FROM kepesertaan WHERE is_temp=1 AND (status_polis=%s OR status_polis=%s) AND tanggal_lahir=%s AND nama=%s",('Inforce','Akseptasi',tanggal_lahir,nama,))
    rowDouble = tableFind.fetchone()
    
    # if rowDouble:
    #     print("%s \n",rowDouble)
    # else:
    #   print("not double")

    # find rate
    # tableFindRate = mydb.cursor()
    # tableFindRate.execute("SELECT rate FROM rate WHERE tahun=%s AND bulan=%s AND polis_id=%s",(usia,masa_bulan,polis_id))
    # rowRate = tableFindRate.fetchone()
    # if rowRate:
    #   rate =  rowRate[0]
    #   kontribusi = int(nilai_manfaat_asuransi) * int(float(rate)) / 1000
    # else:
    rate = 0
    kontribusi = 0

    #updatePeserta = mydb.cursor()
    #updatePeserta.execute("UPDATE kepesertaan SET is_hitung=%s,rate=%s,kontribusi=%s WHERE id=%s",(1,rate,kontribusi,kepesertaan_id))

  pusher_client.trigger('pengajuan', 'generate', {'message': 'Data berhasil dikalkukasi','polis_id':polis_id,'transaction_id':transaction_id})

if __name__ == "__main__":
    polis_id = int(sys.argv[1])
    transaction_id = int(sys.argv[2])
    calculate(polis_id,transaction_id)
