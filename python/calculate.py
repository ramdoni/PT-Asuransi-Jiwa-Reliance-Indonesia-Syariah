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

def calculate(polis_id,transaction_id,iuran_tabbaru,ujrah):
  # table.execute("SELECT nama,tanggal_lahir FROM kepesertaan WHERE is_temp=1 limit 0,1")
  table.execute("SELECT kepesertaan.id,kepesertaan.nama,kepesertaan.tanggal_lahir,kepesertaan.usia,kepesertaan.masa_bulan,kepesertaan.basic,rate_em FROM kepesertaan \
                  WHERE is_temp=1 AND polis_id=%s",(polis_id,))
  no=0
  kepesertaan = table.fetchall()

  # find rate
  tableFindRate = mydb.cursor()
  # tableFindRate.execute("SELECT rate FROM rate WHERE tahun=%s AND bulan=%s AND polis_id=%s LIMIT 1",(usia,masa_bulan,polis_id))
  tableFindRate.execute("SELECT tahun,bulan,rate FROM rate WHERE polis_id=%s",(polis_id,))
  rowRates = tableFindRate.fetchall()
  
  for item in kepesertaan:
    kepesertaan_id = item[0]
    nama = item[1]
    tanggal_lahir = item[2]
    usia = item[3] 
    masa_bulan = item[4]
    nilai_manfaat_asuransi = item[5]
    rate_em = item[6]
    is_double = 0
    akumulasi_ganda = 0
    uw = ""
    no = no+1 

    print(str(no) +". "+nama)

    if nilai_manfaat_asuransi=="" or nilai_manfaat_asuransi == 0:
      continue

    #find double data
    tableFind = mydb.cursor()
    tableFind.execute("SELECT sum(basic) FROM kepesertaan WHERE (status_polis=%s OR status_polis=%s OR status_akseptasi=%s) AND tanggal_lahir=%s AND nama=%s",('Inforce','Akseptasi',1,tanggal_lahir,nama,))
    rowDouble = tableFind.fetchone()

    if rowDouble[0]!=None:
      is_double = 1
      akumulasi_ganda = int(rowDouble[0]) + int(nilai_manfaat_asuransi)

    rate = 0
    kontribusi = 0
    dana_tabarru = 0
    dana_ujrah = 0
    extra_mortalita = 0
    for rowRate in rowRates:
      if int(rowRate[0])==int(usia) and int(rowRate[1])==int(masa_bulan):
        rate =  rowRate[2]
        kontribusi = int(nilai_manfaat_asuransi) * float(rate) / 1000
        dana_tabarru = (kontribusi*iuran_tabbaru)/100;
        dana_ujrah = (kontribusi*ujrah)/100;
        if rate_em:
            extra_mortalita = rate_em*nilai_manfaat_asuransi/1000;

    tableUw = mydb.cursor()
    if akumulasi_ganda !=0:
      tableUw.execute("SELECT keterangan FROM underwriting_limit WHERE (%s BETWEEN min_amount AND max_amount) AND usia=%s AND polis_id=%s LIMIT 1",(akumulasi_ganda,usia,polis_id,))
    else:
      tableUw.execute("SELECT keterangan FROM underwriting_limit WHERE (%s BETWEEN min_amount AND max_amount) AND usia=%s AND polis_id=%s LIMIT 1",(nilai_manfaat_asuransi,usia,polis_id,))
    
    rowUw = tableUw.fetchone()
    if rowUw:
      uw = rowUw[0];

    updatePeserta = mydb.cursor()
    updatePeserta.execute("UPDATE kepesertaan SET is_hitung=%s, rate=%s, kontribusi=%s,dana_tabarru=%s,dana_ujrah=%s,extra_mortalita=%s,is_double=%s,akumulasi_ganda=%s,uw=%s,ul=%s WHERE id=%s",(1,rate,kontribusi,dana_tabarru,dana_ujrah,extra_mortalita,is_double,akumulasi_ganda,uw,uw,kepesertaan_id,))
    mydb.commit()

  pusher_client.trigger('pengajuan', 'generate', {'message': 'Data berhasil dikalkukasi','polis_id':polis_id,'transaction_id':transaction_id})

if __name__ == "__main__":
    polis_id = int(sys.argv[1])
    transaction_id = sys.argv[2]
    iuran_tabbaru = float(sys.argv[3])
    ujrah = float(sys.argv[4])
    calculate(polis_id,transaction_id,iuran_tabbaru,ujrah)
