from PIL import Image, ImageDraw, ImageFont
import os

# Ultra High Resolution Canvas: 3200 x 2200 for maximum clarity
W, H = 3200, 2200
img = Image.new('RGB', (W, H), '#FAF8F5')
draw = ImageDraw.Draw(img)

try:
    font_bold = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 20)
    font_med = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 16)
    font_sm = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 14)
    font_title = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 34)
    font_legend = ImageFont.truetype("C:/Windows/Fonts/segoeuib.ttf", 18)
except:
    font_bold = ImageFont.load_default()
    font_med = ImageFont.load_default()
    font_sm = ImageFont.load_default()
    font_title = ImageFont.load_default()
    font_legend = ImageFont.load_default()

CREAM = '#C4A574'
BLACK = '#141414'
DARK = '#262224'
GRAY = '#5E5659'
LIGHT_GRAY = '#8C8286'
WHITE = '#FFFFFF'
BORDER = '#DCD3C5'

TABLE_COLORS = {
    'auth': '#141414',
    'vendor': '#9A7432',
    'booking': '#1D4ED8',
    'invitation': '#7C3AED',
    'finance': '#0E7036',
    'content': '#C2410C',
    'system': '#4B5563',
}

# Top Banner Header
draw.rectangle([(0, 0), (W, 100)], fill=BLACK)
draw.text((50, 22), "BrightDor — Entity Relationship Diagram (ERD)", fill='#EEDDB8', font=font_title)
draw.text((50, 68), "37 Tabel Relasional (Sinkronisasi September 2026)  |  Laravel 11 + Filament v5  |  MySQL / SQLite  |  Spatie Media Library", fill='#BFB7BA', font=font_med)

class Table:
    def __init__(self, name, cols, x, y, color_key='auth', w=360):
        self.name = name
        self.cols = cols
        self.x = x
        self.y = y
        self.color = TABLE_COLORS[color_key]
        self.w = w
        self.h = 42 + len(cols) * 28

    def draw(self):
        # Header Box
        draw.rounded_rectangle([(self.x, self.y), (self.x + self.w, self.y + 38)], radius=8, fill=self.color)
        draw.text((self.x + 14, self.y + 8), self.name, fill=WHITE, font=font_bold)
        
        # Body Box
        draw.rounded_rectangle([(self.x, self.y + 38), (self.x + self.w, self.y + self.h)], radius=8, fill=WHITE, outline=BORDER, width=2)
        
        for i, col in enumerate(self.cols):
            y_pos = self.y + 46 + i * 28
            # Alternate row background
            if i % 2 == 1:
                draw.rectangle([(self.x + 2, y_pos - 4), (self.x + self.w - 2, y_pos + 22)], fill='#FBF9F5')
                
            prefix = "PK" if col[1] else ("FK" if col[2] else "  ")
            prefix_color = '#DC2626' if col[1] else ('#2563EB' if col[2] else LIGHT_GRAY)
            draw.text((self.x + 12, y_pos), prefix, fill=prefix_color, font=font_sm)
            draw.text((self.x + 44, y_pos), col[0], fill=DARK, font=font_med)
            if col[3]:
                draw.text((self.x + self.w - 110, y_pos), col[3], fill=LIGHT_GRAY, font=font_sm)

def draw_arrow(x1, y1, x2, y2, color=LIGHT_GRAY, label=None):
    draw.line([(x1, y1), (x2, y2)], fill=color, width=3)
    if label:
        mx = (x1 + x2) // 2
        my = (y1 + y2) // 2 - 18
        draw.rectangle([(mx - 4, my - 2), (mx + len(label) * 8 + 4, my + 18)], fill=WHITE)
        draw.text((mx, my), label, fill=DARK, font=font_sm)

# Column 1: Auth & Vendor Category (x = 50)
t_users = Table('users', [
    ('id', True, False, 'bigint'),
    ('name', False, False, 'string'),
    ('email', False, False, 'string UQ'),
    ('user_type', False, False, 'enum'),
    ('status', False, False, 'enum'),
    ('phone', False, False, 'string'),
    ('avatar', False, False, 'string'),
    ('vendor_sub_status', False, False, 'string'),
    ('vendor_expires_at', False, False, 'timestamp'),
    ('password', False, False, 'string'),
    ('created_at', False, False, 'timestamp'),
], 50, 140, 'auth', 360)

t_tokens = Table('password_reset_tokens', [
    ('email', True, False, 'string'),
    ('token', False, False, 'string'),
    ('created_at', False, False, 'timestamp'),
], 50, 560, 'auth', 360)

t_sessions = Table('sessions', [
    ('id', True, False, 'string'),
    ('user_id', False, True, 'FK → users'),
    ('ip_address', False, False, 'string'),
    ('user_agent', False, False, 'text'),
    ('last_activity', False, False, 'integer'),
], 50, 720, 'auth', 360)

t_roles = Table('roles & permissions', [
    ('id', True, False, 'bigint'),
    ('name', False, False, 'string'),
    ('guard_name', False, False, 'string'),
    ('model_has_roles', False, False, 'pivot'),
    ('role_has_permissions', False, False, 'pivot'),
], 50, 940, 'auth', 360)

t_commission = Table('commission_settings', [
    ('id', True, False, 'bigint'),
    ('vendor_category_id', False, True, 'FK → categories'),
    ('rate_percent', False, False, 'decimal(5,2)'),
    ('is_active', False, False, 'boolean'),
], 50, 1180, 'finance', 360)

t_blogs = Table('blogs & CMS', [
    ('id', True, False, 'bigint'),
    ('user_id', False, True, 'FK → users'),
    ('title', False, False, 'string'),
    ('slug', False, False, 'string UQ'),
    ('status', False, False, 'enum'),
    ('published_at', False, False, 'timestamp'),
], 50, 1380, 'content', 360)

t_banners = Table('banners & testimonials', [
    ('id', True, False, 'bigint'),
    ('title / name', False, False, 'string'),
    ('rating', False, False, 'integer'),
    ('position', False, False, 'string'),
    ('is_active', False, False, 'boolean'),
], 50, 1640, 'content', 360)

# Column 2: Vendors & Documents (x = 470)
t_vendors = Table('vendors', [
    ('id', True, False, 'bigint'),
    ('user_id', False, True, 'FK UQ → users'),
    ('vendor_category_id', False, True, 'FK → categories'),
    ('business_name', False, False, 'string'),
    ('slug', False, False, 'string UQ'),
    ('city', False, False, 'string'),
    ('province', False, False, 'string'),
    ('rating_avg', False, False, 'decimal(3,2)'),
    ('rating_count', False, False, 'integer'),
    ('status', False, False, 'enum'),
    ('is_verified', False, False, 'boolean'),
    ('is_featured', False, False, 'boolean'),
    ('bank_account_number', False, False, 'string'),
], 470, 140, 'vendor', 380)

t_categories = Table('vendor_categories', [
    ('id', True, False, 'bigint'),
    ('name', False, False, 'string'),
    ('slug', False, False, 'string UQ'),
    ('commission_rate', False, False, 'decimal(5,2)'),
    ('is_active', False, False, 'boolean'),
], 470, 620, 'vendor', 380)

t_documents = Table('vendor_documents', [
    ('id', True, False, 'bigint'),
    ('vendor_id', False, True, 'FK → vendors'),
    ('document_type', False, False, 'string'),
    ('file_path', False, False, 'string'),
    ('status', False, False, 'enum'),
    ('reviewed_at', False, False, 'timestamp'),
], 470, 850, 'vendor', 380)

t_payouts = Table('payouts', [
    ('id', True, False, 'bigint'),
    ('payout_code', False, False, 'string UQ'),
    ('vendor_id', False, True, 'FK → vendors'),
    ('amount', False, False, 'decimal(15,2)'),
    ('net_amount', False, False, 'decimal(15,2)'),
    ('status', False, False, 'enum'),
    ('processed_by', False, True, 'FK → users'),
], 470, 1110, 'finance', 380)

t_faqs = Table('faqs & galleries', [
    ('id', True, False, 'bigint'),
    ('question / title', False, False, 'string'),
    ('category', False, False, 'string'),
    ('is_active', False, False, 'boolean'),
], 470, 1410, 'content', 380)

# Column 3: Services & Bookings (x = 910)
t_services = Table('services', [
    ('id', True, False, 'bigint'),
    ('vendor_id', False, True, 'FK → vendors'),
    ('name', False, False, 'string'),
    ('price', False, False, 'decimal(15,2)'),
    ('discount_price', False, False, 'decimal(15,2)'),
    ('capacity', False, False, 'integer'),
    ('duration', False, False, 'string'),
    ('is_active', False, False, 'boolean'),
    ('status', False, False, 'enum'),
], 910, 140, 'vendor', 380)

t_bookings = Table('bookings', [
    ('id', True, False, 'bigint'),
    ('booking_code', False, False, 'string UQ'),
    ('user_id', False, True, 'FK → users'),
    ('vendor_id', False, True, 'FK → vendors'),
    ('service_id', False, True, 'FK → services'),
    ('event_date', False, False, 'date'),
    ('subtotal', False, False, 'decimal(15,2)'),
    ('admin_fee', False, False, 'decimal(15,2)'),
    ('total_amount', False, False, 'decimal(15,2)'),
    ('status', False, False, 'enum'),
], 910, 500, 'booking', 380)

t_reviews = Table('reviews', [
    ('id', True, False, 'bigint'),
    ('booking_id', False, True, 'FK → bookings UQ'),
    ('vendor_id', False, True, 'FK → vendors'),
    ('user_id', False, True, 'FK → users'),
    ('rating', False, False, 'integer (1-5)'),
    ('content', False, False, 'text'),
    ('is_verified', False, False, 'boolean'),
], 910, 890, 'booking', 380)

t_transactions = Table('transactions', [
    ('id', True, False, 'bigint'),
    ('transaction_code', False, False, 'string UQ'),
    ('user_id', False, True, 'FK → users'),
    ('payable_type', False, False, 'polymorphic'),
    ('payable_id', False, False, 'polymorphic ID'),
    ('amount', False, False, 'decimal(15,2)'),
    ('payment_gateway', False, False, 'enum'),
    ('status', False, False, 'enum'),
    ('paid_at', False, False, 'timestamp'),
], 910, 1180, 'finance', 380)

# Column 4: Invitations (x = 1350)
t_inv_templates = Table('invitation_templates', [
    ('id', True, False, 'bigint'),
    ('category_id', False, True, 'FK → categories'),
    ('name', False, False, 'string'),
    ('price', False, False, 'decimal(15,2)'),
    ('demo_url', False, False, 'string'),
    ('is_premium', False, False, 'boolean'),
    ('is_active', False, False, 'boolean'),
], 1350, 140, 'invitation', 380)

t_inv_orders = Table('invitation_orders', [
    ('id', True, False, 'bigint'),
    ('order_code', False, False, 'string UQ'),
    ('user_id', False, True, 'FK → users'),
    ('template_id', False, True, 'FK → templates'),
    ('bride_name', False, False, 'string'),
    ('groom_name', False, False, 'string'),
    ('price', False, False, 'decimal(15,2)'),
    ('status', False, False, 'enum'),
], 1350, 440, 'invitation', 380)

t_invitations = Table('invitations', [
    ('id', True, False, 'bigint'),
    ('order_id', False, True, 'FK → orders'),
    ('user_id', False, True, 'FK → users'),
    ('slug', False, False, 'string UQ'),
    ('content', False, False, 'json'),
    ('views_count', False, False, 'integer'),
    ('rsvp_yes', False, False, 'integer'),
    ('is_published', False, False, 'boolean'),
], 1350, 760, 'invitation', 380)

t_rsvps = Table('invitation_rsvps', [
    ('id', True, False, 'bigint'),
    ('invitation_id', False, True, 'FK → invitations'),
    ('guest_name', False, False, 'string'),
    ('attendance', False, False, 'enum(yes,no,maybe)'),
    ('guest_count', False, False, 'integer'),
    ('message', False, False, 'text'),
], 1350, 1080, 'invitation', 380)

# Column 5: System & Media (x = 1790)
t_media = Table('media (Spatie Polymorphic)', [
    ('id', True, False, 'bigint'),
    ('model_type', False, False, 'polymorphic class'),
    ('model_id', False, False, 'polymorphic id'),
    ('collection_name', False, False, 'string (portfolio,logo)'),
    ('file_name', False, False, 'string'),
    ('mime_type', False, False, 'string'),
    ('disk', False, False, 'string (public)'),
    ('size', False, False, 'bigint'),
], 1790, 140, 'system', 400)

t_audit = Table('audit_logs', [
    ('id', True, False, 'bigint'),
    ('user_id', False, True, 'FK → users'),
    ('action', False, False, 'string'),
    ('model', False, False, 'string'),
    ('old_values', False, False, 'json'),
    ('new_values', False, False, 'json'),
    ('ip_address', False, False, 'string'),
], 1790, 480, 'system', 400)

t_notifs = Table('notifications', [
    ('id', True, False, 'uuid PK'),
    ('type', False, False, 'string'),
    ('notifiable_type', False, False, 'polymorphic'),
    ('notifiable_id', False, False, 'polymorphic ID'),
    ('data', False, False, 'text (JSON)'),
    ('read_at', False, False, 'timestamp'),
], 1790, 780, 'system', 400)

t_settings = Table('settings', [
    ('id', True, False, 'bigint'),
    ('group', False, False, 'string'),
    ('key', False, False, 'string UQ'),
    ('value', False, False, 'text'),
    ('type', False, False, 'string'),
], 1790, 1060, 'system', 400)

t_queue = Table('jobs, failed_jobs & cache', [
    ('id / key', True, False, 'bigint / string'),
    ('queue / payload', False, False, 'string / text'),
    ('attempts / expiration', False, False, 'integer'),
    ('failed_at / value', False, False, 'timestamp / text'),
], 1790, 1300, 'system', 400)

all_tables = [
    t_users, t_tokens, t_sessions, t_roles, t_commission, t_blogs, t_banners,
    t_vendors, t_categories, t_documents, t_payouts, t_faqs,
    t_services, t_bookings, t_reviews, t_transactions,
    t_inv_templates, t_inv_orders, t_invitations, t_rsvps,
    t_media, t_audit, t_notifs, t_settings, t_queue
]

for t in all_tables:
    t.draw()

# Relationship connectors with high visibility
draw_arrow(410, 240, 470, 240, CREAM, "1:1 (user_id)")
draw_arrow(850, 240, 910, 240, CREAM, "1:N (vendor_id)")
draw_arrow(660, 560, 660, 620, CREAM, "FK (category_id)")
draw_arrow(1100, 450, 1100, 500, '#2563EB', "FK (service_id)")
draw_arrow(1100, 820, 1100, 890, '#2563EB', "1:1 (booking_id)")
draw_arrow(1100, 1120, 1100, 1180, '#16A34A', "payable (morph)")
draw_arrow(1540, 390, 1540, 440, '#7C3AED', "1:N (template_id)")
draw_arrow(1540, 700, 1540, 760, '#7C3AED', "1:1 (order_id)")
draw_arrow(1540, 1020, 1540, 1080, '#7C3AED', "1:N (invitation_id)")

# Legend & Notes Box
legend_y = 1920
draw.rounded_rectangle([(50, legend_y), (W - 50, legend_y + 240)], radius=12, fill=WHITE, outline=BORDER, width=2)
draw.text((80, legend_y + 20), "Keterangan Warna & Simbol ERD:", fill=BLACK, font=font_legend)

legend_items = [
    ('auth', '1. Autentikasi & Pengguna'),
    ('vendor', '2. Sistem Mitra Vendor'),
    ('booking', '3. Pemesanan (Booking) & Review'),
    ('invitation', '4. Undangan Digital'),
    ('finance', '5. Transaksi Keuangan & Payout'),
    ('content', '6. CMS (Blog, Banner, Testimoni)'),
    ('system', '7. Sistem, Log Audit & Media Spatie'),
]

lx = 80
ly = legend_y + 60
for i, (key, label) in enumerate(legend_items):
    if i == 4:
        lx = 80
        ly += 45
    draw.rounded_rectangle([(lx, ly), (lx + 24, ly + 24)], radius=4, fill=TABLE_COLORS[key])
    draw.text((lx + 34, ly + 2), label, fill=DARK, font=font_med)
    lx += 380

draw.text((80, ly + 50), "Simbol:  [PK] = Primary Key  |  [FK] = Foreign Key Relasi  |  [UQ] = Nilai Unik  |  [morph] = Spatie Polymorphic Relation (bisa mengait ke Vendor, Service, atau Invitation)", fill=GRAY, font=font_sm)

OUT_PATH = r"C:\Users\ADVAN\brightdor\public\presentasi\assets\erd.png"
img.save(OUT_PATH, quality=95)
print(f"Large ERD successfully saved to: {OUT_PATH}")
