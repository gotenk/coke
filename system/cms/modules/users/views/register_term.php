    <div class="register-term-wrapper">
        <h1 class="all white" style="margin:30px 0 10px 0">Syarat & Ketentuan</h1>
        <h4 class="all white" style="margin:0 15%">Dengan menggunakan salah satu akun Sosial Media, berarti saya telah menyetujui untuk menyertakan video karya sendiri untuk berpartisipasi dalam #MeriahBersama COCA-COLA.</h3>
        <div class="form-registrasi">       
            <?php echo form_open(uri_string(),array('id'=>'form-register')); ?>
            <table id="registrasi">
                <tr>
                    <td><label>Sosial Media</label></td>
                    <td>:</td>
                    <td><a href="javascript:void(0);" data-sosmed="facebook" class="sosmed-icon-reg">{{theme:image file="ico_reg_fb.png"}}</a> <a class="sosmed-icon-reg" data-sosmed="twitter" href="javascript:void(0);">{{ theme:image file="ico_reg_twi.png"}}</a> <a class="sosmed-icon-reg" href="javascript:void(0);" data-sosmed="vine">{{ theme:image file="ico_reg_vi.png" }}</a> <a class="sosmed-icon-reg" href="javascript:void(0);" data-sosmed="instagram" >{{ theme:image file="ico_reg_inst.png"}}</a></td>
                    <?php echo form_error('social_media', '<div class="error">', '</div>'); ?>
                </tr>
            
                <tr class="vine-wrap" style="display:none;" >
                        <td><label>Vine Username</label></td>
                        <td>:</td>
                        <td>
                            <?php echo form_input('vine_username',isset($vine_username) ? $vine_username: '','class="reg"') ?>
                            <?php echo form_error('vine_username', '<div class="error">', '</div>'); ?>
                        </td>
                
                 </tr>
                <tr class="vine-wrap" style="display:none;" >
                    <td><label>Vine Password</label></td>
                    <td>:</td>
                    <td>
                        <?php echo form_password('vine_password',isset($vine_password) ? $vine_password: '','class="reg"') ?>
                        <?php echo form_error('vine_password', '<div class="error">', '</div>'); ?>
                    </td>
                    
                </tr>
                
                 <tr>
                    <td><label>Tanggal Lahir</label></td>
                    <td>:</td>
                    <td>
                        <?php echo $day_list.' '.$month_list.' '.$year_list; ?>
                        <?php echo form_error('dob', '<div class="error">', '</div>'); ?>
                    </td>
                    
                </tr>
                <tr class="parent-email-wrap" style="display:none;">
                    <td><label>Email Orang Tua</label></td>
                    <td>:</td>
                    <td><?php echo form_input('parent_email',(isset($parent_email)? $parent_email: ''),'class="reg"' ); ?><?php echo form_error('parent_email', '<div class="error">', '</div>'); ?></td>
                </tr>
                <tr>
                    <td><label>Recaptcha</label></td>
                    <td>:</td>
                    <td>{{ recaptcha:get_recaptcha is_checkbox="true" }} <?php echo form_error('g-recaptcha-response', '<div class="error">', '</div>'); ?></td>
                </tr>
                <tr class="syarat">
                    <td colspan="2" align="right"></td>
                    <td ><?php echo form_checkbox('tnc','true',$tnc)?>Setuju dengan <a href="https://ramadan.coca-cola.co.id/persyaratan-dan-ketentuan" target="_blank" style="text-decoration: underline; color: #fff;">Syarat dan Ketentuan</a> dan <a href="http://www.coca-cola.co.id/id/kebijakan-privasi/" target="_blank" style="text-decoration: underline; color: #fff;">Kebijakan Privasi</a> yang ada <?php echo form_error('tnc', '<div class="error">', '</div>'); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><input type="submit" value="Kirim" class="reg" /></td>
                </tr>
            </table>
            <?php echo form_hidden('social_media',$social_media);
                  echo form_hidden('dob',$dob); 
            ?>
            <?php echo form_close() ?>
        </div>
        <div id="wrap-registrasi" class="nano">
            <div class="nano-content">
                <div class="text-registrasi">
                <h1>PROGRAM INI DITUJUKAN UNTUK DISAKSIKAN DI NEGARA KESATUAN REPUBLIK INDONESIA DAN PERATURAN INI HANYA BOLEH DITAFSIRKAN DALAM BAHASA INDONESIA MENURUT HUKUM YANG BERLAKU DI REPUBLIK INDONESIA.</h1>
                <p class="sub-registrasi">Kompetisi Video "Meriah Bersama" - Peraturan Resmi
                    <ol id="term">
                        <li><b>Kompetisi:</b><br/> 
                            Kompetisi ini adalah kompetisi video dengan nama "KOMPETISI VIDEO MERIAH BERSAMA" ("Kompetisi”) yang diselenggarakan oleh PT Coca-Cola Indonesia ("Penyelenggara") dan dilaksanakan oleh pihak yang ditunjuk Penyelenggara untuk penyelenggaraan Kompetisi (“Panitia”). Karya adalah video yang kamu buat sesuai dengan Peraturan Resmi ini dan dikirimkan ke page <b>Facebook, Twitter, Instagram dan Vine</b> pribadi peserta dengan menggunakan #MeriahBersama. Khusus untuk Facebook, karya yang dikirimkan dengan <b>#MeriahBersama</b> harus diatur menjadi "Public Post" sehingga bisa diakses oleh pihak Panitia.
                        </li>
                        <li><b>Kelayakan Peserta:</b><br/>
                            Program ini terbuka bagi setiap orang yang memenuhi persyaratan sebagai berikut:
                            <ul>
                                <li>Warga Negara Indonesia; </li>
                                <li>Bertempat tinggal di wilayah Republik Indonesia; </li>
                                <li>Berusia lebih dari 13 tahun. Bagi peserta dengan umur dibawah dua puluh satu (21) tahun harus mendapatkan persetujuan tertulis terlebih dahulu dari orang tua atau walinya yang sah untuk mengikuti Kompetisi ini, dan harus menunjukkan persetujuan tertulis tersebut ketika diminta oleh Penyelenggara. Formulir persetujuan orang tua atau walinya yang sah dapat dilihat pada Lampiran I;</li>
                                <li>Memiliki kartu identitas diri yang masih berlaku (KTP atau Paspor atau Kartu Pelajar).</li>
                                <li>Bukan anggota keluarga atau karyawan dari Panitia, Penyelenggara dan afiliasinya, termasuk perusahaan pembotol, distributor, agen periklanannya ("Pihak yang Memiliki Konflik").  Yang dimaksud keluarga adalah suami atau istri, orang tua, kakak atau adik, kakek atau nenek, anak, cucu, mertua, atau anggota keluarga yang tinggal bersama Pihak yang Memiliki Konflik atau yang dari segi keuangan mengandalkan Pihak yang Memiliki Konflik atau orang yang diandalkan oleh Pihak yang Memiliki Konflik dari segi keuangan; dan</li>
                                <li>Memiliki akun Facebook dan menjadi fans Facebook COCA-COLA.</li>
                            </ul>
                        </li>
                        <li><b>Periode Pengumpulan Karya:</b><br/>
                            Periode pengumpulan Karya dimulai pada 00:00 Waktu Indonesia Bagian Barat ("WIB") pada 18 Mei 2015 ("Kompetisi Dimulai") dan berakhir pada 23:59 WIB, pada 23 Juli 2015 ("Kompetisi Berakhir"). Jam komputer Panitia adalah jam resmi untuk Kompetisi dan semua Karya yang masuk akan ditandai dengan waktu yang sesuai.
                        </li>
                        <li><b>Cara Ikut Serta:</b>
                            <ul>
                                <li>Anda harus terlebih dahulu mengunduh aplikasi dan memiliki akun di Facebook dan/atau Twitter dan/atau Instagram dan/atau Vine dan menjadi fans di akun Facebook Fan Page Coca Cola Indonesia atau follow Twitter @CocaCola_id, dan/atau Instagram @CocaColaid.</li>
                                <li>Selanjutnya Anda dapat membuat video dengan teman-teman atau keluarga Anda bertema Meriah Bersama dengan menyertakan botol COCA-COLA edisi Ramadan dalam Karya Anda, dan memenuhi kriteria dalam Peraturan Resmi ini.</li>
                                <li>Anda dapat mengunggah (<em>upload</em>) Karya Anda melalui akun Facebook dan/atau Twitter dan/atau Instagram dan/atau Vine milik anda dengan menggunakan hashtag #MeriahBersama.  Selanjutnya Anda harus melakukan pendaftaran di website  CokeURL.com/tncmb ("Website") dan menyebutkan (<em>mention/tag</em>) masing-masing akun sosial media pilihan Anda, seperti untuk Facebook Fan Page Coca-Cola dan/atau Twitter @CocaCola_ID dan/atau Instagram @CocaCola_ID dan/atau Vine @CocaCola_ID.</li>
                                <li>Khusus untuk Facebook, karya yang diunggah (<em>upload</em>) dengan #MeriahBersama harus diatur menjadi "Public Post" sehingga bisa diakses oleh Panitia.</li>
                            </ul>
                            Tidak ada batas minimum, Anda dapat mengunggah Karya Anda sebanyak-banyaknya untuk diajukan dalam Kompetisi ini. Karya Anda akan dinilai oleh Dewan Juri melalui beberapa kriteria tertentu. Anda dapat mengajukan Karya sebanyak yang Anda inginkan selama Periode Pengumpulan Karya, tetapi masing-masing Karya harus merupakan video yang unik dan berbeda dan harus diajukan terpisah untuk masing-masing Karya. Anda akan diminta untuk melengkapi identitas anda, alamat surat termasuk kota, propinsi, kode pos, nomor telepon yang dapat dihubungi, alamat email dimana anda menjadi pengguna sah dari data-data tersebut dan untuk menerima, Kebijakan Privasi Penyelenggara, media social yang digunakan untuk mengunggah Karya Anda (yaitu Facebook, Twitter, Instagram dan Vine), dan Peraturan Resmi ini untuk ikut serta. Setiap Karya harus didasari kriteria Kompetisi sebagaimana disebutkan di Peraturan #5. Anda dapat mengikuti Karya Kompetisi sesering yang Anda inginkan selama Periode Pengumpulan Karya. Anda mempunyai kebebasan berkreasi sepenuhnya untuk membuat Karya, dengan syarat pembatasan yang ditetapkan di Peraturan #5 dan #6  di bawah ini. Semua Karya, termasuk video yang tidak memenuhi syarat kelayakan Kompetisi dan tidak berhak atas hadiah, menjadi milik Penyelenggara dan Penyelenggara berhak untuk menggunakannya untuk kepentingan Penyelenggara apapun, termasuk untuk ditempatkan di media apapun baik yang diketahui saat ini maupun di masa yang akan datang, tanpa batasan apapun, termasuk internet, Facebook, Twitter, Instagram, Vine atau media jejaring sosial lainnya, baik untuk kepentingan komersial maupun non komersial sehubungan dengan Kompetisi ini, tanpa imbalan. Dengan menyerahkan Karya sesuai dengan ketentuan Peraturan #4 ini, Anda beserta setiap orang yang tampil dalam Karya anda mengalihkan semua hak, kepemilikan dan kepentingan atas Karya, termasuk Hak Ciptanya, tanpa imbalan, kepada Penyelenggara dan induk perusahaan Penyelenggara, yaitu The Coca-Cola Company, dan Penyelengara maupun The Coca-Cola Company akan terus menjadi pemilik eksklusif atas semua Hak Cipta Karya Anda beserta semua hak terkaitnya di seluruh dunia dan sepanjang masa. Dengan ini Anda juga mengesampingkan hak untuk mewajibkan Penyelenggara maupun The Coca-Cola Company untuk mencantumkan nama Anda pada pemakaian Karya.
                        </li>
                        <li><b>Persyaratan Karya:</b> Video harus:  (i) menunjukkan Anda dan/atau keluarga dan/atau teman-teman Anda menikmati COCA-COLA dan menunjukkan kemasan COCA-COLA edisi Ramadan, menunjukkan momen yang terjadi saat bulan Ramadan, diantaranya mulai dari persiapan menjelang puasa, saat bulan puasa berlangsung hingga hari Raya Lebaran;  (ii) harus dibuat dalam format landscape dengan durasi minimal 6 detik maksimal 15 detik; (iii) asli dan tidak melanggar hak pihak ketiga manapun termasuk, tetapi tidak terbatas pada hak cipta, hak merek dagang, atau hak privasi atau publisitas, atau hak-hak atas kekayaan intelektual lainnya;  (iv) diajukan dan didaftarkan oleh satu individu peserta, dengan ketentuan bahwa Anda memiliki hak sepenuhnya dalam dan atas video Anda; (v) belum pernah ditayangkan sebelumnya, dan belum pernah memenangkan penghargaan atau kompetisi sebelumnya; (vi) tidak mempromosikan alkohol, obat-obatan ilegal, rokok, senjata/senjata api (atau menggunakannya), atau mempromosikan aktivitas apapun yang terlihat ilegal, tidak aman atau berbahaya; (vii) tidak mempromosikan agenda atau pesan politik apapun; (viii) tidak mengandung informasi identitas perorangan yang dapat dikenali mengenai seseorang lain selain peserta, seperti nomor plat kendaraan, nama pribadi, alamat e-mail, atau alamat tempat tinggal. Jika peserta menyertakan identitas personal dirinya, peserta menerima dan menyetujui bahwa informasi-informasi tersebut dapat diungkapkan ke publik dan peserta sepenuhnya bertanggung jawab atas konsekuensinya; (ix) tidak melanggar hukum apapun; (x) tidak menjurus ke pornografi, cabul atau aktifitas yang tidak pantas, panggilan atau konotasi rasis atau seksual, bahasa yang meghina, kecabulan atau materi apapun yang memfitnah atau mencemarkan nama baik, atau dapat menyinggung, atau meremehkan sebuah perkumpulan atau individu, termasuk, tanpa batasan, perilaku meremehkan ras, jender atau agama; (xi) tidak menunjukkan nama merek, logo atau merek dagang selain milik Penyelenggara, yang mana peserta mempunyai izin terbatas untuk menggunakannya semata-mata untuk kepentingan menciptakan dan mengunggah video dalam Kompetisi ini; dan (xii) tidak meremehkan atau menyalahi COCA-COLA atau, simbol dan logo milik COCA-COLA atau orang atau pihak lain yang berhubungan dengan Kompetisi dan Penyelenggara. Panitia dan Penyelenggara memiliki hak untuk tidak mempertimbangkan, dan untuk menghilangkan, video apapun yang mereka anggap, semata-mata karena, pertimbangan subjektif mereka, sebagai tidak pantas dan/atau tidak mematuhi Peraturan Resmi dan tidak akan melakukan diskusi atau komunikasi dalam bentuk apapun dengan peserta tersebut atau siapapun sehubungan dengan video tersebut. Seluruh keputusan Panitia dan Penyelenggara adalah final dan mengikat dan tidak dapat ditarik kembali.  Peserta bertanggung jawab penuh atas keikutsertaannya dalam Kompetisi ini dan akan melepaskan dan membebaskan Penyelenggara, induk perusahaan dan perusahaan afiliasinya dari segala tuntutan dalam bentuk apapun yang timbul (termasuk yang diakibatkan oleh kelalaian), baik yang secara langsung maupun tidak langsung timbul diakibatkan dari hasil video yang dibuat dan keiikutsertaannya dalam Kompetisi ini.</li>
                        <li><b>Kriteria Seleksi dan Penjurian:</b> Karya harus memenuhi persyaratan Karya sebagaimana tertera dalam peraturan poin 5 dan menunjukkan keunggulan berdasarkan kriteria sebagai berikut:
                            <ul>
                                <li>Relevan dengan COCA-COLA dan tema Meriah Bersama (50%).</li>
                                <li>Fun dan kreatif (50%).</li>
                            </ul>
                            Seluruh penjurian akan dilakukan oleh Dewan Juri yang berasal dari Penyelenggara.
                        </li>
                        <li><b>Seleksi Karya dan Penjurian Pemenang:</b> Setiap karya akan dipantau oleh Panitia untuk kepatuhan terhadap persyaratan dan standar yang ditentukan seterusnya dalam Peraturan Resmi ini.
                            <ul class="alphabet-list">
                                <li><b>Seleksi Karya:</b> Penyelenggara dan Panitia akan menyeleksi semua Karya yang diajukan berdasarkan kriteria yang ditetapkan dalam Peraturan poin 5 dan 6 di atas ini untuk menentukan sejumlah Karya yang telah lolos seleksi untuk kemudian dilakukan penjurian. Karya yang telah lolos seleksi akan diunggah di halaman Galeri di Website sebagai Karya yang berhak masuk ke tahap penjurian. Sejumlah Karya yang terpilih dan lolos seleksi akan dipilih oleh Panitia dan Penyelenggara untuk menjadi bagian dalam video kompilasi yang akan dibuat oleh Penyelenggara dengan judul Meriah Bersama.</li>
                                <li><b>Penjurian Pemenang:</b> Seluruh penjurian atas hasil seleksi Panitia akan dilakukan oleh Dewan Juri yang ditentukan Panitia dan/atau Penyelenggara berdasarkan kriteria yang telah disebutkan di Peraturan poin 6 di atas dan akan diumumkan di akun Facebook dan/atau Twitter COCA-COLA @CocaCola_ID setiap minggunya sesuai jadwal di bawah ini.</li>
								<style type="text/css">
									.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
									.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
									.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
									.tg .tg-s6z2{text-align:center;vertical-align:middle;}
									.tg .tg-ipa1{font-weight:bold;background-color:#c0c0c0;text-align:center;vertical-align:middle;}
								</style>
								<table class="tg">
								  <tr>
								    <th class="tg-ipa1">Periode</th>
								    <th class="tg-ipa1">Periode Pengumpulan Karya setiap minggunya</th>
								    <th class="tg-ipa1">Tanggal Penjurian</th>
								    <th class="tg-ipa1">Tanggal Pengumuman</th>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode I</td>
								    <td class="tg-031e">Minggu I,<br>Dari tanggal 13 May 2015 pukul 00:00 WIB sampai tanggal 28 May 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">29 May 2015</td>
								    <td class="tg-s6z2">2 Juni 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode II</td>
								    <td class="tg-031e">Minggu II,<br>Dari tanggal 29 May 2015 pukul,00:00 WIB sampai tanggal 4 Juni 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">5 Juni 2015</td>
								    <td class="tg-s6z2">9 Juni 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode III</td>
								    <td class="tg-031e">Minggu III,<br>Dari tanggal 5 Juni 2015 pukul,00:00 WIB sampai tanggal 11 Juni 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">12 Juni 2015</td>
								    <td class="tg-s6z2">16 Juni 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode IV</td>
								    <td class="tg-031e">Minggu IV,<br>Dari tanggal 12 Juni 2015 pukul,00:00 WIB sampai tanggal 18 Juni 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">19 Juni 2015</td>
								    <td class="tg-s6z2">23 Juni 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode V</td>
								    <td class="tg-031e">Minggu V,<br>Dari tanggal 19 Juni 2015 pukul,00:00 WIB sampai tanggal 25 Juni 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">26 Juni 2015</td>
								    <td class="tg-s6z2">30 Juni 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode VI</td>
								    <td class="tg-031e">Minggu VI,<br>Dari tanggal 26 Juni 2015 pukul,00:00 WIB sampai tanggal 2 Juli 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">3 Juli 2015</td>
								    <td class="tg-s6z2">7 Juli 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode VII</td>
								    <td class="tg-031e">Minggu VII,<br>Dari tanggal 3 Juli 2015 pukul,00:00 WIB sampai tanggal 9 Juli 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">10 Juli 2015</td>
								    <td class="tg-s6z2">14 Juli 2015</td>
								  </tr>
								  <tr>
								    <td class="tg-s6z2">Periode VIII</td>
								    <td class="tg-031e">Minggu VIII,<br>Dari tanggal 10 Juli 2015 pukul,00:00 WIB sampai tanggal 23 Juli 2015 pukul 23:59 WIB</td>
								    <td class="tg-s6z2">24 Juli 2015</td>
								    <td class="tg-s6z2">28 Juli 2015</td>
								  </tr>
								</table><br>
                            </ul>
							Dewan Juri akan memilih 5 Karya sebagai pemenang setiap minggunya selama Periode Kompetisi. Keputusan Panitia dan Dewan Juri adalah final dan mutlak. Para peserta tidak memiliki hak untuk menggugat keputusan Panitia dan Dewan Juri dan tidak akan terlibat dalam pembicaraan, negosiasi atau memberi mengenai keputusan Dewan Juri.                            
                        </li>
                        <li><b>Pengumuman dan Pemberitahuan Pemenang:</b> Semua Pemenang Kompetisi akan diumumkan melalui akun Facebook dan/atau Twitter COCA-COLA, pada tanggal-tanggal berikut:
                        	<ul style="list-style-type: none; font-weight: bold;">
	                        	<li>(i) Periode I: 2 Juni 2015</li>
								<li>(ii) Periode II: 9 Juni 2015</li>
								<li>(iii) Periode III: 16 Juni 2015</li>
								<li>(iv) Periode IV: 23 Juni 2015</li>
								<li>(v) Periode V: 30 Juni 2015</li>
								<li>(vi) Periode VI: 3 Juli 2015</li>
								<li>(vii) Periode VII: 14 Juli 2015</li>
								<li>(viii) Periode VIII: 28 Juli 2015</li>
                        	</ul>
						Setiap pemenang akan dihubungi oleh pihak Panitia dan/atau Penyelenggara melalui <em>inbox message</em> Facebook Fan Page COCA-COLA dan/atau <em>direct message</em> Twitter COCA-COLA untuk proses verifikasi dan mekanisme pengambilan hadiah. Pemenang tidak akan diminta untuk melakukan pembayaran apapun untuk mengambil hadiahnya.
                        </li>
                        <li><b>Jumlah Hadiah:</b><br>
                            5 Karya akan dipilih menjadi Pemenang setiap minggunya atau 40 Karya akan dipilih menjadi Pemenang selama Periode Kompetisi, dimana masing-masing Pemenang akan mendapatkan hadiah berupa satu (1) buah boneka Polar Bear dari Coca-Cola ("Hadiah"). Sejumlah Karya yang terpilih akan mendapatkan berbagai hadiah menarik dari Coca-Cola. Berbagai pengeluaran lain yang berhubungan dengan pengambilan atau penerimaan hadiah atau penggunaan hadiah yang tidak disebutkan disini akan ditanggung oleh pemenang. Apabila Pemenang Hadiah masih di bawah usia 21 tahun maka Pemenang harus memberikan Surat Izin Tertulis dari orang tuanya atau pihak walinya yang sah kepada pihak Panitia.<br>
                            Tidak akan ada penggantian atau kompensasi hadiah atau bagian dari hadiah yang telah dibatalkan karena peserta yang terpilih tidak dapat memenuhi peraturan Kompetisi atau karena sebab lain. Hak untuk mendapatkan hadiah tidak dapat dipindahkan atau digantikan orang lain. Hadiah tidak dapat diuangkan; tidak ada hak menuntut balik. Tidak ada hadiah pengganti, uang tunai seharga hadiah, pemindahan atau penggantian yang diperbolehkan.
                        </li>
                    </ol>
                </p>
                <p>Dengan mengikuti Kompetisi ini, Anda (dan jika adalah anak di bawah umur, orang tua dan wali anda yang sah) mengetahui bahwa hadiah akan diberikan "Apa adanya" dan Penyelenggara dan Panitia tidak memberi jaminan, garansi atau perwakilan dalam bentuk apapun, secara tersebut atau tersirat, secara fakta maupun hukum, sehubungan dengan hadiah atau penggunaan hadiah, nilai atau keuntungan dari hadiah.</p>
                <p><b>Seluruh merek-merek dagang adalah milik pemilik masing-masing merek tersebut.</b></p>
                </div>
                
            </div>
        </div><!--end wrap alamat-->
    </div>
    
<script type="text/javascript">
	$('#content-wrapper').backstretch("{{ theme:image_url file="bg-promo.jpg" }}");
	$(".nano").nanoScroller();
	</script>