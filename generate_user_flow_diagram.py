import os
from PIL import Image, ImageDraw, ImageFont

# Canvas dimensions: 3200 x 2400 (Ultra High Definition)
W, H = 3200, 2400
img = Image.new('RGB', (W, H), '#FAF8F5')
draw = ImageDraw.Draw(img)

# Fonts
try:
    font_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 36)
    font_subtitle = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 18)
    font_lane_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 24)
    font_lane_desc = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 15)
    font_box_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 18)
    font_box_step = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 13)
    font_box_body = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 14)
    font_badge = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 12)
    font_legend = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 16)
except:
    font_title = ImageFont.load_default()
    font_subtitle = ImageFont.load_default()
    font_lane_title = ImageFont.load_default()
    font_lane_desc = ImageFont.load_default()
    font_box_title = ImageFont.load_default()
    font_box_step = ImageFont.load_default()
    font_box_body = ImageFont.load_default()
    font_badge = ImageFont.load_default()
    font_legend = ImageFont.load_default()

# Colors
COLOR_BG = '#FAF8F5'
COLOR_BORDER = '#DCD3C5'
COLOR_DARK = '#141213'
COLOR_MUTED = '#5E5659'
COLOR_LIGHT_MUTED = '#8C8286'
COLOR_WHITE = '#FFFFFF'

# Lane Themes
THEMES = {
    'couple': {
        'primary': '#B22246',    # Rose Wine
        'header_bg': '#8E1B37',
        'card_bg': '#FFFFFF',
        'card_border': '#F1D0D8',
        'badge_bg': '#FDF2F4',
        'badge_text': '#8E1B37',
        'arrow': '#B22246'
    },
    'vendor': {
        'primary': '#A88647',    # Gold / Amber
        'header_bg': '#8A6D3B',
        'card_bg': '#FFFFFF',
        'card_border': '#EBDCBF',
        'badge_bg': '#FBF6EC',
        'badge_text': '#8A6D3B',
        'arrow': '#A88647'
    },
    'admin': {
        'primary': '#374151',    # Slate / Charcoal
        'header_bg': '#1F2937',
        'card_bg': '#FFFFFF',
        'card_border': '#D1D5DB',
        'badge_bg': '#F3F4F6',
        'badge_text': '#1F2937',
        'arrow': '#4B5563'
    }
}

# 1. Top Header Banner
draw.rectangle([(0, 0), (W, 120)], fill='#141213')
draw.text((60, 24), "BrightDor — Diagram Alur Kerja Sistem & Interaksi Pengguna (User Flow)", fill='#EEDDB8', font=font_title)
draw.text((60, 76), "Integrasi End-to-End: Calon Pengantin (Couple)  •  Mitra Usaha (Vendor)  •  Pengelola Platform (Admin)", fill='#BFB7BA', font=font_subtitle)

# Helper function to draw rounded boxes with shadow-like border
def draw_card(x, y, w, h, title, step_num, lines, theme_key, badge_text=None):
    t = THEMES[theme_key]
    
    # Base Box
    draw.rounded_rectangle([(x, y), (x + w, y + h)], radius=12, fill=t['card_bg'], outline=t['card_border'], width=2)
    
    # Top Accent Bar
    draw.rounded_rectangle([(x, y), (x + w, y + 8)], radius=4, fill=t['primary'])
    
    # Step Badge
    draw.rounded_rectangle([(x + 16, y + 18), (x + 86, y + 42)], radius=6, fill=t['badge_bg'], outline=t['card_border'])
    draw.text((x + 24, y + 23), f"TAHAP {step_num}", fill=t['badge_text'], font=font_box_step)
    
    # Optional Status Tag
    if badge_text:
        tw = len(badge_text) * 8 + 16
        draw.rounded_rectangle([(x + w - 16 - tw, y + 18), (x + w - 16, y + 42)], radius=6, fill='#E8F7EE', outline='#C4EDD4')
        draw.text((x + w - 16 - tw + 8, y + 23), badge_text, fill='#0E623B', font=font_badge)
    
    # Card Title
    draw.text((x + 16, y + 54), title, fill=COLOR_DARK, font=font_box_title)
    
    # Card Body Lines
    curr_y = y + 88
    for line in lines:
        draw.text((x + 16, curr_y), line, fill=COLOR_MUTED, font=font_box_body)
        curr_y += 24

# Helper function for horizontal flow arrow
def draw_flow_arrow(x1, y1, x2, y2, color):
    draw.line([(x1, y1), (x2, y2)], fill=color, width=4)
    # Arrow head
    draw.polygon([(x2, y2), (x2 - 12, y2 - 7), (x2 - 12, y2 + 7)], fill=color)

# Helper function for cross-lane interaction arrow (curved/dashed style)
def draw_cross_interaction(x1, y1, x2, y2, label, color='#7C3AED'):
    draw.line([(x1, y1), (x2, y2)], fill=color, width=3)
    # Circle at start, arrow at end
    draw.ellipse([(x1 - 5, y1 - 5), (x1 + 5, y1 + 5)], fill=color)
    if y2 > y1:
        draw.polygon([(x2, y2), (x2 - 6, y2 - 10), (x2 + 6, y2 - 10)], fill=color)
    else:
        draw.polygon([(x2, y2), (x2 - 6, y2 + 10), (x2 + 6, y2 + 10)], fill=color)
    
    # Interaction label box
    mx = (x1 + x2) // 2
    my = (y1 + y2) // 2
    lbl_w = len(label) * 8 + 20
    draw.rounded_rectangle([(mx - lbl_w // 2, my - 13), (mx + lbl_w // 2, my + 13)], radius=6, fill='#FFFFFF', outline=color, width=2)
    draw.text((mx - lbl_w // 2 + 10, my - 8), label, fill=color, font=font_badge)

# Define Swimlane Heights
LANE_COUPLE_Y = 160
LANE_VENDOR_Y = 880
LANE_ADMIN_Y = 1600
LANE_H = 680

# LANE 1: COUPLE (Calon Pengantin)
draw.rounded_rectangle([(40, LANE_COUPLE_Y), (W - 40, LANE_COUPLE_Y + LANE_H)], radius=16, fill='#FFFBFD', outline='#F1D0D8', width=2)
# Lane Header
draw.rounded_rectangle([(40, LANE_COUPLE_Y), (420, LANE_COUPLE_Y + 70)], radius=12, fill='#8E1B37')
draw.text((60, LANE_COUPLE_Y + 14), "1. ALUR PENGGUNA: COUPLE", fill=COLOR_WHITE, font=font_lane_title)
draw.text((60, LANE_COUPLE_Y + 44), "End-user / Calon pengantin pencari jasa pernikahan", fill='#FBECEF', font=font_lane_desc)

# Lane 1 Cards (7 Steps across 5 columns layout)
# Step C1
draw_card(460, LANE_COUPLE_Y + 40, 360, 220, "Eksplorasi & Filter", "01", [
    "• Akses katalog di beranda / vendor",
    "• Filter kota & kategori (Venue, MUA, dll)",
    "• Lihat rating, harga paket termurah",
    "• Navbar sticky liquid glass interaktif"
], 'couple', "Pencarian")

# Step C2
draw_card(870, LANE_COUPLE_Y + 40, 360, 220, "Registrasi / Login", "02", [
    "• Buat akun Couple di /register",
    "• Password hashing satu arah (Bcrypt)",
    "• Fitur 'Lupa Password' mandiri",
    "• Session aman (Anti-CSRF & XSS)"
], 'couple', "Akun Aman")

# Step C3
draw_card(1280, LANE_COUPLE_Y + 40, 360, 220, "Detail Profil Vendor", "03", [
    "• Masuk ke /vendor/{slug}",
    "• Galeri portofolio (Spatie Media)",
    "• Evaluasi daftar paket & fasilitas",
    "• Cek ulasan & rating bintang asli"
], 'couple', "Evaluasi")

# Step C4
draw_card(1690, LANE_COUPLE_Y + 40, 360, 220, "Pemesanan / Penawaran", "04", [
    "• Opsi A: 'Booking Tanggal' pasti",
    "• Opsi B: 'Ajukan Penawaran' khusus",
    "• Modal responsif di atas navbar (z-999)",
    "• Terbit kode unik booking di DB"
], 'couple', "Transaksi")

# Step C5
draw_card(2100, LANE_COUPLE_Y + 40, 360, 220, "Pembayaran Transaksi", "05", [
    "• Otomatis via Payment Gateway (Midtrans)",
    "• ATAU transfer manual verifikasi",
    "• Potongan komisi platform tercatat",
    "• Status beralih ke 'Confirmed'"
], 'couple', "Payment")

# Step C6 & C7 (Row 2 in Lane 1)
draw_card(2100, LANE_COUPLE_Y + 380, 360, 220, "Pelacakan 'Booking Saya'", "06", [
    "• Pantau progres di /booking-saya",
    "• Status: Pending -> Confirmed -> On Progress",
    "• Hak membatalkan jika belum disetujui",
    "• Notifikasi pembaruan status jadwal"
], 'couple', "Monitoring")

draw_card(1690, LANE_COUPLE_Y + 380, 360, 220, "Selesai & Beri Ulasan", "07", [
    "• Acara terlaksana (Status 'Completed')",
    "• Buka form review resmi (Rating 1-5)",
    "• Relasi 1 booking = 1 review unik",
    "• Otomatis mendongkrak rating vendor"
], 'couple', "Rating Asli")

# Arrows inside Couple Lane
draw_flow_arrow(820, LANE_COUPLE_Y + 150, 870, LANE_COUPLE_Y + 150, THEMES['couple']['arrow'])
draw_flow_arrow(1230, LANE_COUPLE_Y + 150, 1280, LANE_COUPLE_Y + 150, THEMES['couple']['arrow'])
draw_flow_arrow(1640, LANE_COUPLE_Y + 150, 1690, LANE_COUPLE_Y + 150, THEMES['couple']['arrow'])
draw_flow_arrow(2050, LANE_COUPLE_Y + 150, 2100, LANE_COUPLE_Y + 150, THEMES['couple']['arrow'])
# Turn down to Step 6
draw.line([(2280, LANE_COUPLE_Y + 260), (2280, LANE_COUPLE_Y + 380)], fill=THEMES['couple']['arrow'], width=4)
draw.polygon([(2280, LANE_COUPLE_Y + 380), (2273, LANE_COUPLE_Y + 368), (2287, LANE_COUPLE_Y + 368)], fill=THEMES['couple']['arrow'])
# Step 6 to Step 7 (Right to Left)
draw_flow_arrow(2100, LANE_COUPLE_Y + 490, 2050, LANE_COUPLE_Y + 490, THEMES['couple']['arrow'])


# LANE 2: VENDOR (Mitra Usaha Pernikahan)
draw.rounded_rectangle([(40, LANE_VENDOR_Y), (W - 40, LANE_VENDOR_Y + LANE_H)], radius=16, fill='#FDFCF9', outline='#EBDCBF', width=2)
# Lane Header
draw.rounded_rectangle([(40, LANE_VENDOR_Y), (420, LANE_VENDOR_Y + 70)], radius=12, fill='#8A6D3B')
draw.text((60, LANE_VENDOR_Y + 14), "2. ALUR MITRA: VENDOR", fill=COLOR_WHITE, font=font_lane_title)
draw.text((60, LANE_VENDOR_Y + 44), "Penyedia jasa pernikahan: fotografer, venue, MUA, dll", fill='#F9F4E8', font=font_lane_desc)

# Lane 2 Cards
# Step V1
draw_card(460, LANE_VENDOR_Y + 40, 360, 220, "Daftar Mitra Vendor", "01", [
    "• Akses form /daftar-vendor",
    "• Hubungkan akun user (1 User = 1 Vendor)",
    "• Pilih kategori spesifik & kota operasional",
    "• Status awal vendor: 'pending'"
], 'vendor', "Registrasi")

# Step V2
draw_card(870, LANE_VENDOR_Y + 40, 360, 220, "Upload Dokumen Legal", "02", [
    "• Unggah KTP, NIB, atau Sertifikasi",
    "• Berkas masuk tabel vendor_documents",
    "• Menunggu verifikasi dari Admin",
    "• Proteksi dokumen di storage privat"
], 'vendor', "Verifikasi")

# Step V3
draw_card(1280, LANE_VENDOR_Y + 40, 360, 220, "Setup Profil & Paket", "03", [
    "• Kelola etalase di /vendor/dashboard",
    "• Tambah paket jasa di tabel services",
    "• Atur harga, diskon, kapasitas & fasilitas",
    "• Upload cover & portofolio (Spatie Media)"
], 'vendor', "Etalase Aktif")

# Step V4
draw_card(1690, LANE_VENDOR_Y + 40, 360, 220, "Terima Pesanan Masuk", "04", [
    "• Dapatkan notifikasi booking baru",
    "• Tinjau tanggal acara & detail pasangan",
    "• Jika penawaran: ajukan estimasi harga",
    "• Konfirmasi / Jadwalkan Ulang / Tolak"
], 'vendor', "Persetujuan")

# Step V5
draw_card(2100, LANE_VENDOR_Y + 40, 360, 220, "Eksekusi Layanan Acara", "05", [
    "• Pesanan disetujui (Status 'Confirmed')",
    "• Jadwal terkunci di kalender kerja",
    "• Berikan layanan prima saat resepsi",
    "• Update status menjadi 'Completed'"
], 'vendor', "Penyelesaian")

# Step V6 (Row 2 in Lane 2)
draw_card(2100, LANE_VENDOR_Y + 380, 360, 220, "Pengajuan Payout Saldo", "06", [
    "• Saldo bersih masuk setelah potong komisi",
    "• Ajukan pencairan di menu Payouts",
    "• Input rekening bank (BCA, Mandiri, dll)",
    "• Diproses transfer oleh tim Admin"
], 'vendor', "Pencairan Dana")

# Arrows inside Vendor Lane
draw_flow_arrow(820, LANE_VENDOR_Y + 150, 870, LANE_VENDOR_Y + 150, THEMES['vendor']['arrow'])
draw_flow_arrow(1230, LANE_VENDOR_Y + 150, 1280, LANE_VENDOR_Y + 150, THEMES['vendor']['arrow'])
draw_flow_arrow(1640, LANE_VENDOR_Y + 150, 1690, LANE_VENDOR_Y + 150, THEMES['vendor']['arrow'])
draw_flow_arrow(2050, LANE_VENDOR_Y + 150, 2100, LANE_VENDOR_Y + 150, THEMES['vendor']['arrow'])
# Turn down to Step 6
draw.line([(2280, LANE_VENDOR_Y + 260), (2280, LANE_VENDOR_Y + 380)], fill=THEMES['vendor']['arrow'], width=4)
draw.polygon([(2280, LANE_VENDOR_Y + 380), (2273, LANE_VENDOR_Y + 368), (2287, LANE_VENDOR_Y + 368)], fill=THEMES['vendor']['arrow'])


# LANE 3: ADMIN (Platform Superuser)
draw.rounded_rectangle([(40, LANE_ADMIN_Y), (W - 40, LANE_ADMIN_Y + LANE_H)], radius=16, fill='#F9FAFB', outline='#D1D5DB', width=2)
# Lane Header
draw.rounded_rectangle([(40, LANE_ADMIN_Y), (420, LANE_ADMIN_Y + 70)], radius=12, fill='#1F2937')
draw.text((60, LANE_ADMIN_Y + 14), "3. ALUR PENGELOLA: ADMIN", fill=COLOR_WHITE, font=font_lane_title)
draw.text((60, LANE_ADMIN_Y + 44), "Superuser pengendali operasional, verifikasi, & keuangan", fill='#E5E7EB', font=font_lane_desc)

# Lane 3 Cards
# Step A1
draw_card(460, LANE_ADMIN_Y + 40, 360, 220, "Login Filament Panel", "01", [
    "• Akses panel admin di /admin/login",
    "• Guard autentikasi terisolasi dari publik",
    "• Akses monitoring ringkasan omset & tren",
    "• Menu navigasi terstruktur rapi (Ctrl+K)"
], 'admin', "Superuser")

# Step A2
draw_card(870, LANE_ADMIN_Y + 40, 360, 220, "Moderasi & Dokumen", "02", [
    "• Review berkas legalitas di tabel documents",
    "• Setujui (Approve) / Tolak (Reject) berkas",
    "• Sematkan badge 'Verified' pada vendor",
    "• Pilihlah mitra unggulan ('Featured')"
], 'admin', "Kurasi Mitra")

# Step A3
draw_card(1280, LANE_ADMIN_Y + 40, 360, 220, "Validasi Pembayaran", "03", [
    "• Semi-Auto: Webhook gateway otomatis",
    "• Manual: Verifikasi 1-klik mutasi transfer",
    "• Cegah pesanan palsu / belum lunas",
    "• Pencatatan polimorfik di transactions"
], 'admin', "Semi-Auto")

# Step A4
draw_card(1690, LANE_ADMIN_Y + 40, 360, 220, "Bagi Komisi & Finansial", "04", [
    "• Potong otomatis komisi platform (10%)",
    "• Simpan sisa saldo bersih untuk vendor",
    "• Audit keuangan terpusat real-time",
    "• Catat transaksi di tabel audit_logs"
], 'admin', "Otomatis")

# Step A5
draw_card(2100, LANE_ADMIN_Y + 40, 360, 220, "Eksekusi Payout Vendor", "05", [
    "• Buka antrian pencairan dana mitra",
    "• Validasi kesesuaian nomor rekening",
    "• Konfirmasi transfer dana selesai",
    "• Status payout berubah ke 'Paid Out'"
], 'admin', "Pencairan")

# Step A6 & A7 (Row 2 in Lane 3)
draw_card(1280, LANE_ADMIN_Y + 380, 360, 220, "Manajemen User & Akun", "06", [
    "• Awasi seluruh user (Couple, Vendor, Admin)",
    "• Blokir akun yang melanggar ketentuan",
    "• TIDAK mengubah password user (Self-Service)",
    "• Pantau status lisensi langganan vendor"
], 'admin', "Pengawasan")

draw_card(1690, LANE_ADMIN_Y + 380, 360, 220, "Audit Log & Sistem CMS", "07", [
    "• Rekam metadata forensik (IP & User-Agent)",
    "• Snapshot data sebelum vs sesudah edit",
    "• Kelola artikel blog, banner promo, FAQ",
    "• Optimasi resource & performa server"
], 'admin', "Forensik & CMS")

# Arrows inside Admin Lane
draw_flow_arrow(820, LANE_ADMIN_Y + 150, 870, LANE_ADMIN_Y + 150, THEMES['admin']['arrow'])
draw_flow_arrow(1230, LANE_ADMIN_Y + 150, 1280, LANE_ADMIN_Y + 150, THEMES['admin']['arrow'])
draw_flow_arrow(1640, LANE_ADMIN_Y + 150, 1690, LANE_ADMIN_Y + 150, THEMES['admin']['arrow'])
draw_flow_arrow(2050, LANE_ADMIN_Y + 150, 2100, LANE_ADMIN_Y + 150, THEMES['admin']['arrow'])


# CROSS-LANE INTERACTIONS (Hubungan Kunci Antar-Role)
# 1. Vendor Doc Upload (V2) -> Admin Verification (A2)
draw_cross_interaction(1050, LANE_VENDOR_Y + 260, 1050, LANE_ADMIN_Y + 40, "Verifikasi Dokumen", '#7C3AED')

# 2. Couple Booking (C4) -> Vendor Receives Booking (V4)
draw_cross_interaction(1870, LANE_COUPLE_Y + 260, 1870, LANE_VENDOR_Y + 40, "Kirim Booking / Nego", '#B22246')

# 3. Couple Payment (C5) -> Admin Validation (A3)
draw_cross_interaction(2180, LANE_COUPLE_Y + 260, 1460, LANE_ADMIN_Y + 40, "Validasi Pembayaran", '#2563EB')

# 4. Vendor Payout Request (V6) -> Admin Payout Execution (A5)
draw_cross_interaction(2280, LANE_VENDOR_Y + 600, 2280, LANE_ADMIN_Y + 40, "Proses Transfer Payout", '#0E7036')

# 5. Couple Review (C7) -> Updates Vendor Rating (V3)
draw_cross_interaction(1750, LANE_COUPLE_Y + 600, 1460, LANE_VENDOR_Y + 40, "Auto-Update Rating", '#C2410C')


# BOTTOM LEGEND & METADATA BAR
legend_y = 2300
draw.rectangle([(0, legend_y), (W, H)], fill='#141213')
draw.text((60, legend_y + 30), "Keterangan Warna Jalur:", fill=COLOR_WHITE, font=font_legend)

draw.rounded_rectangle([(280, legend_y + 26), (304, legend_y + 50)], radius=4, fill='#B22246')
draw.text((314, legend_y + 28), "Alur Couple (Customer)", fill='#EEDDB8', font=font_box_body)

draw.rounded_rectangle([(560, legend_y + 26), (584, legend_y + 50)], radius=4, fill='#A88647')
draw.text((594, legend_y + 28), "Alur Mitra (Vendor)", fill='#EEDDB8', font=font_box_body)

draw.rounded_rectangle([(820, legend_y + 26), (844, legend_y + 50)], radius=4, fill='#4B5563')
draw.text((854, legend_y + 28), "Alur Pengelola (Admin)", fill='#EEDDB8', font=font_box_body)

draw.rounded_rectangle([(1090, legend_y + 26), (1114, legend_y + 50)], radius=4, fill='#7C3AED')
draw.text((1124, legend_y + 28), "Interaksi Silang Antar-Role", fill='#EEDDB8', font=font_box_body)

draw.text((1500, legend_y + 28), "Prinsip Utama: Role-Based Access Control • Pembayaran Semi-Auto • Self-Service Security • 100% Terintegrasi", fill='#9CA3AF', font=font_box_body)

# Save Image
OUT_PATH = r"C:\Users\ADVAN\brightdor\public\presentasi\assets\user_flow_diagram.png"
img.save(OUT_PATH, quality=95)
print(f"User flow diagram saved successfully to: {OUT_PATH}")
