import os
from PIL import Image, ImageDraw, ImageFont

# Dimensions: 2600 x 1850 (Clean, crisp, consumer-friendly infographic)
W, H = 2600, 1850
img = Image.new('RGB', (W, H), '#FAF8F5')
draw = ImageDraw.Draw(img)

# Fonts
try:
    font_hero = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 40)
    font_subhero = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 20)
    font_section = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 26)
    font_section_sub = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 16)
    font_step_num = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 28)
    font_card_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 20)
    font_card_body = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 15)
    font_card_body_bold = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 15)
    font_guarantee_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 19)
    font_guarantee_body = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 15)
    font_badge = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 13)
except:
    font_hero = ImageFont.load_default()
    font_subhero = ImageFont.load_default()
    font_section = ImageFont.load_default()
    font_section_sub = ImageFont.load_default()
    font_step_num = ImageFont.load_default()
    font_card_title = ImageFont.load_default()
    font_card_body = ImageFont.load_default()
    font_card_body_bold = ImageFont.load_default()
    font_guarantee_title = ImageFont.load_default()
    font_guarantee_body = ImageFont.load_default()
    font_badge = ImageFont.load_default()

# Colors
C_DARK = '#141213'
C_MUTED = '#5A5255'
C_WINE = '#8E1B37'
C_WINE_LIGHT = '#FDF2F4'
C_WINE_BORDER = '#F1D0D8'
C_GOLD = '#9A7432'
C_GOLD_LIGHT = '#FBF6ED'
C_GOLD_BORDER = '#EBDCBF'
C_GREEN = '#0E7036'
C_GREEN_LIGHT = '#E8F7EE'
C_GREEN_BORDER = '#C4EDD4'
C_BLUE = '#1E40AF'
C_BLUE_LIGHT = '#EFF6FF'
C_BLUE_BORDER = '#BFDBFE'

# 1. TOP HEADER BANNER
draw.rectangle([(0, 0), (W, 140)], fill='#8E1B37')
draw.text((80, 30), "CARA KERJA BRIGHTDOR — MUDAH, AMAN & TRANSPARAN", fill='#FFFFFF', font=font_hero)
draw.text((80, 85), "Panduan Alur Layanan Bagi Calon Pengantin & Mitra Vendor Tanpa Ribet", fill='#FBECEF', font=font_subhero)

# Badge on header right
draw.rounded_rectangle([(W - 480, 42), (W - 80, 98)], radius=28, fill='#A62846')
draw.text((W - 455, 57), "100% BEBAS REKAYASA & AMAN", fill='#FFFFFF', font=font_badge)


# ==============================================================================
# SECTION 1: ALUR CALON PENGANTIN (COUPLE)
# ==============================================================================
SEC1_Y = 175
draw.text((80, SEC1_Y), "1. Alur Calon Pengantin (Bagaimana Cara Memesan?)", fill=C_WINE, font=font_section)
draw.text((80, SEC1_Y + 38), "Hanya 5 langkah sederhana dari mencari inspirasi sampai pernikahan impian terwujud.", fill=C_MUTED, font=font_section_sub)

# 5 Cards for Couple: Width 450 each, Gap 35 -> 5 * 450 + 4 * 35 = 2250 + 140 = 2390 (Fits nicely with 105px side margins)
COUPLE_STEPS = [
    {
        "num": "1",
        "title": "Cari & Pilih Vendor",
        "desc": [
            "Buka website BrightDor dari HP / laptop.",
            "Pilih kategori yang kamu butuhkan:",
            "Gedung, Foto, Rias/MUA, Katering, dll.",
            "Filter berdasarkan kota & budget kamu."
        ],
        "highlight": "Banyak pilihan terpercaya"
    },
    {
        "num": "2",
        "title": "Cek Foto & Nego Harga",
        "desc": [
            "Lihat foto hasil karya (portofolio) vendor.",
            "Baca ulasan & bintang dari pengantin asli.",
            "Pilih paket yang sudah tersedia,",
            "atau klik 'Ajukan Penawaran' untuk nego."
        ],
        "highlight": "Harga & fasilitas transparan"
    },
    {
        "num": "3",
        "title": "Kunci Tanggal & Bayar",
        "desc": [
            "Tentukan tanggal acara pernikahanmu.",
            "Lakukan pembayaran dengan aman.",
            "Uang TIDAK langsung ke vendor,",
            "melainkan ditampung aman oleh sistem."
        ],
        "highlight": "Jaminan uang aman 100%"
    },
    {
        "num": "4",
        "title": "Vendor Siap Melayani",
        "desc": [
            "Vendor menerima jadwal & bersiap.",
            "Pantau status pesananmu secara jelas.",
            "Vendor hadir di hari pernikahanmu",
            "dan memberikan pelayanan terbaik."
        ],
        "highlight": "Jadwal pernikahan terkunci"
    },
    {
        "num": "5",
        "title": "Acara Beres & Beri Nilai",
        "desc": [
            "Resepsi pernikahan berjalan lancar.",
            "Sistem meneruskan pembayaran ke vendor.",
            "Berikan bintang & ulasan jujur kamu",
            "untuk membantu calon pengantin lainnya!"
        ],
        "highlight": "Ulasan bantu pasangan lain"
    }
]

card_w = 460
card_h = 360
start_x = 80
card_y = SEC1_Y + 75

for i, step in enumerate(COUPLE_STEPS):
    cx = start_x + i * (card_w + 35)
    
    # White Card Box
    draw.rounded_rectangle([(cx, card_y), (cx + card_w, card_y + card_h)], radius=16, fill='#FFFFFF', outline=C_WINE_BORDER, width=2)
    
    # Top Card Bar
    draw.rounded_rectangle([(cx, card_y), (cx + card_w, card_y + 8)], radius=4, fill=C_WINE)
    
    # Circle Step Number
    draw.ellipse([(cx + 24, card_y + 24), (cx + 80, card_y + 80)], fill=C_WINE_LIGHT, outline=C_WINE, width=2)
    draw.text((cx + 42, card_y + 36), step['num'], fill=C_WINE, font=font_step_num)
    
    # Tag
    draw.rounded_rectangle([(cx + 95, card_y + 35), (cx + card_w - 24, card_y + 68)], radius=6, fill=C_WINE_LIGHT)
    draw.text((cx + 107, card_y + 44), step['highlight'], fill=C_WINE, font=font_badge)
    
    # Title
    draw.text((cx + 24, card_y + 105), step['title'], fill=C_DARK, font=font_card_title)
    
    # Body lines
    by = card_y + 155
    for line in step['desc']:
        draw.text((cx + 24, by), "• " + line, fill=C_MUTED, font=font_card_body)
        by += 32
        
    # Connecting arrow to next card
    if i < 4:
        ax1 = cx + card_w + 6
        ax2 = cx + card_w + 29
        ay = card_y + 180
        draw.line([(ax1, ay), (ax2, ay)], fill=C_WINE, width=4)
        draw.polygon([(ax2, ay), (ax2 - 10, ay - 6), (ax2 - 10, ay + 6)], fill=C_WINE)


# ==============================================================================
# SECTION 2: ALUR MITRA VENDOR
# ==============================================================================
SEC2_Y = card_y + card_h + 55
draw.text((80, SEC2_Y), "2. Alur Mitra Usaha (Bagaimana Vendor Bekerja?)", fill=C_GOLD, font=font_section)
draw.text((80, SEC2_Y + 38), "Peluang bagi penyedia jasa pernikahan untuk mendapatkan pelanggan tanpa pusing mencari promosi.", fill=C_MUTED, font=font_section_sub)

VENDOR_STEPS = [
    {
        "num": "A",
        "title": "Daftar & Buka Profil",
        "desc": [
            "Daftar akun sebagai Mitra Vendor.",
            "Isi profil bisnis & upload identitas.",
            "Tim BrightDor memverifikasi keaslian usaha.",
            "Toko online vendor siap tayang!"
        ],
        "highlight": "Pendaftaran Gratis"
    },
    {
        "num": "B",
        "title": "Pasang Foto & Paket",
        "desc": [
            "Upload foto karya terbaik (portofolio).",
            "Buat daftar paket jasa & rincian harga.",
            "Tentukan kapasitas tamu & fasilitas.",
            "Bisa atur diskon khusus promo."
        ],
        "highlight": "Etalase Mandiri"
    },
    {
        "num": "C",
        "title": "Terima Pesanan Masuk",
        "desc": [
            "Dapat notifikasi saat ada pasangan booking.",
            "Tinjau tanggal & lokasi acara.",
            "Bisa menyetujui langsung,",
            "atau balas pesan nego penawaran harga."
        ],
        "highlight": "Order Otomatis Masuk"
    },
    {
        "num": "D",
        "title": "Layani & Terima Dana",
        "desc": [
            "Kerjakan jasa di hari pernikahan klien.",
            "Setelah acara sukses terselenggara,",
            "dana pembayaran langsung cair utuh",
            "ke rekening bank resmi milik vendor!"
        ],
        "highlight": "Pencairan Pasti & Cepat"
    }
]

# 4 Cards for Vendor: Width 575 each, Gap 40 -> 4 * 575 + 3 * 40 = 2300 + 120 = 2420
v_card_w = 575
v_card_h = 320
v_card_y = SEC2_Y + 75

for i, step in enumerate(VENDOR_STEPS):
    vx = start_x + i * (v_card_w + 40)
    
    draw.rounded_rectangle([(vx, v_card_y), (vx + v_card_w, v_card_y + v_card_h)], radius=16, fill='#FFFFFF', outline=C_GOLD_BORDER, width=2)
    draw.rounded_rectangle([(vx, v_card_y), (vx + v_card_w, v_card_y + 8)], radius=4, fill=C_GOLD)
    
    # Circle Step
    draw.ellipse([(vx + 24, v_card_y + 24), (vx + 80, v_card_y + 80)], fill=C_GOLD_LIGHT, outline=C_GOLD, width=2)
    draw.text((vx + 41, v_card_y + 36), step['num'], fill=C_GOLD, font=font_step_num)
    
    # Tag
    draw.rounded_rectangle([(vx + 95, v_card_y + 35), (vx + v_card_w - 24, v_card_y + 68)], radius=6, fill=C_GOLD_LIGHT)
    draw.text((vx + 107, v_card_y + 44), step['highlight'], fill=C_GOLD, font=font_badge)
    
    # Title
    draw.text((vx + 24, v_card_y + 105), step['title'], fill=C_DARK, font=font_card_title)
    
    # Body lines
    by = v_card_y + 155
    for line in step['desc']:
        draw.text((vx + 24, by), "• " + line, fill=C_MUTED, font=font_card_body)
        by += 32
        
    if i < 3:
        ax1 = vx + v_card_w + 8
        ax2 = vx + v_card_w + 32
        ay = v_card_y + 160
        draw.line([(ax1, ay), (ax2, ay)], fill=C_GOLD, width=4)
        draw.polygon([(ax2, ay), (ax2 - 10, ay - 6), (ax2 - 10, ay + 6)], fill=C_GOLD)


# ==============================================================================
# SECTION 3: PERAN BRIGHTDOR / ADMIN (PENJAMIN KEAMANAN KEDUA BELAH PIHAK)
# ==============================================================================
SEC3_Y = v_card_y + v_card_h + 55
draw.text((80, SEC3_Y), "3. Peran BrightDor (Mengapa Lebih Aman Dibanding Pesan Manual?)", fill='#1F2937', font=font_section)
draw.text((80, SEC3_Y + 38), "BrightDor bertindak sebagai penengah dan pelindung terpercaya agar pengantin tidak tertipu dan vendor pasti dibayar.", fill=C_MUTED, font=font_section_sub)

GUARANTEES = [
    {
        "icon": "🛡️",
        "title": "Rekening Penampung Aman (Anti Bawa Kabur)",
        "desc": "Uang pembayaran pengantin disimpan aman di BrightDor terlebih dahulu. Vendor baru menerima bayaran setelah mereka selesai melayani acara pernikahanmu. Jadi tidak ada risiko vendor kabur!"
    },
    {
        "icon": "✅",
        "title": "Vendor Terverifikasi & Asli",
        "desc": "Setiap vendor yang mendaftar wajib menyertakan identitas resmi (KTP/Legalitas Usaha) yang dicek langsung oleh tim BrightDor. Semua ulasan dan bintang adalah nyata dari pengantin asli."
    },
    {
        "icon": "🤝",
        "title": "Bantuan & Mediasi Jika Ada Kendala",
        "desc": "Jika tanggal pernikahan perlu diundur atau ada masalah tak terduga, tim Customer Support BrightDor siap membantu mencarikan jalan tengah terbaik bagi kedua belah pihak."
    }
]

# 3 Guarantee Cards
g_card_w = 780
g_card_h = 190
g_card_y = SEC3_Y + 75

for i, g in enumerate(GUARANTEES):
    gx = start_x + i * (g_card_w + 50)
    
    draw.rounded_rectangle([(gx, g_card_y), (gx + g_card_w, g_card_y + g_card_h)], radius=16, fill='#FFFFFF', outline='#E5E7EB', width=2)
    draw.rounded_rectangle([(gx, g_card_y), (gx + g_card_w, g_card_y + 6)], radius=3, fill='#374151')
    
    draw.text((gx + 24, g_card_y + 24), g['title'], fill='#111827', font=font_guarantee_title)
    
    # Word wrapped body text
    words = g['desc'].split(' ')
    lines = []
    curr = ""
    for w in words:
        test = curr + (" " if curr else "") + w
        if len(test) < 52:
            curr = test
        else:
            lines.append(curr)
            curr = w
    if curr:
        lines.append(curr)
        
    gy = g_card_y + 68
    for l in lines:
        draw.text((gx + 24, gy), l, fill=C_MUTED, font=font_guarantee_body)
        gy += 27


# BOTTOM BAR
draw.rectangle([(0, H - 60), (W, H)], fill='#141213')
draw.text((80, H - 42), "BrightDor Wedding Marketplace  •  Bagan Alur Sederhana Ramah Pengguna  •  Semua Pihak Terlindungi", fill='#EEDDB8', font=font_card_body)
draw.text((W - 480, H - 42), "Dibuat untuk Presentasi & Edukasi Pengguna", fill='#9CA3AF', font=font_card_body)

# Save to docs
out_dir = r"C:\Users\ADVAN\brightdor\docs"
os.makedirs(out_dir, exist_ok=True)
out_file = os.path.join(out_dir, "alur_brightdor_ramah_pengguna.png")
img.save(out_file, quality=95)
print(f"Customer friendly flow image created successfully: {out_file}")
