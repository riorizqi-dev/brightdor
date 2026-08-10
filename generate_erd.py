from PIL import Image, ImageDraw, ImageFont
import os

W, H = 2400, 1600
img = Image.new('RGB', (W, H), '#FAF7F2')
draw = ImageDraw.Draw(img)

try:
    font_bold = ImageFont.truetype("C:/Windows/Fonts/PlusJakartaSans-Bold.ttf", 16)
    font_med = ImageFont.truetype("C:/Windows/Fonts/PlusJakartaSans-Medium.ttf", 13)
    font_sm = ImageFont.truetype("C:/Windows/Fonts/PlusJakartaSans-Regular.ttf", 11)
    font_title = ImageFont.truetype("C:/Windows/Fonts/PlusJakartaSans-Bold.ttf", 24)
except:
    try:
        font_bold = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 16)
        font_med = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 13)
        font_sm = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 11)
        font_title = ImageFont.truetype("C:/Windows/Fonts/segoeui.ttf", 24)
    except:
        font_bold = ImageFont.load_default()
        font_med = ImageFont.load_default()
        font_sm = ImageFont.load_default()
        font_title = ImageFont.load_default()

CREAM = '#C4A574'
BLACK = '#141414'
DARK = '#2A2A2A'
GRAY = '#52525B'
LIGHT_GRAY = '#A1A1AA'
WHITE = '#FFFFFF'
LIGHT_BG = '#F5F0E8'
TABLE_COLORS = {
    'auth': '#141414',
    'vendor': '#C4A574',
    'booking': '#2563EB',
    'invitation': '#8B5CF6',
    'finance': '#16A34A',
    'content': '#EA580C',
    'system': '#71717A',
}

draw.rectangle([(0, 0), (W, 70)], fill=BLACK)
draw.text((40, 20), "BrightDor — Entity Relationship Diagram (ERD)", fill=CREAM, font=font_title)
draw.text((40, 52), "33 Tables  |  Laravel 11 + Filament v5  |  SQLite", fill=LIGHT_GRAY, font=font_sm)

class Table:
    def __init__(self, name, cols, x, y, color_key='auth', w=300):
        self.name = name
        self.cols = cols
        self.x = x
        self.y = y
        self.color = TABLE_COLORS[color_key]
        self.w = w
        self.h = 32 + len(cols) * 22

    def draw(self):
        draw.rounded_rectangle([(self.x, self.y), (self.x + self.w, self.y + 28)], radius=6, fill=self.color)
        draw.text((self.x + 10, self.y + 6), self.name, fill=WHITE, font=font_bold)
        draw.rounded_rectangle([(self.x, self.y + 28), (self.x + self.w, self.y + self.h)], radius=6, fill=WHITE, outline='#E5E5E5')
        for i, col in enumerate(self.cols):
            y_pos = self.y + 34 + i * 22
            prefix = "PK " if col[1] else ("FK " if col[2] else "   ")
            prefix_color = self.color if col[1] else ('#2563EB' if col[2] else GRAY)
            draw.text((self.x + 10, y_pos), prefix, fill=prefix_color, font=font_sm)
            draw.text((self.x + 38, y_pos), col[0], fill=DARK, font=font_med)
            if col[3]:
                draw.text((self.x + self.w - 80, y_pos), col[3], fill=LIGHT_GRAY, font=font_sm)

def draw_arrow(x1, y1, x2, y2, color=LIGHT_GRAY):
    draw.line([(x1, y1), (x2, y2)], fill=color, width=2)

tables = [
    Table('users', [
        ('id', True, False, 'bigint'),
        ('name', False, False, 'string'),
        ('email', False, False, 'string'),
        ('user_type', False, False, 'enum'),
        ('status', False, False, 'enum'),
        ('phone', False, False, 'string'),
    ], 50, 100, 'auth', 280),

    Table('vendors', [
        ('id', True, False, 'bigint'),
        ('user_id', False, True, 'FK → users'),
        ('vendor_category_id', False, True, 'FK → categories'),
        ('business_name', False, False, 'string'),
        ('status', False, False, 'enum'),
        ('is_verified', False, False, 'bool'),
        ('rating_avg', False, False, 'decimal'),
    ], 420, 100, 'vendor', 320),

    Table('services', [
        ('id', True, False, 'bigint'),
        ('vendor_id', False, True, 'FK → vendors'),
        ('name', False, False, 'string'),
        ('price', False, False, 'decimal'),
        ('status', False, False, 'enum'),
        ('is_active', False, False, 'bool'),
    ], 820, 100, 'vendor', 280),

    Table('bookings', [
        ('id', True, False, 'bigint'),
        ('booking_code', False, False, 'string'),
        ('user_id', False, True, 'FK → users'),
        ('vendor_id', False, True, 'FK → vendors'),
        ('service_id', False, True, 'FK → services'),
        ('event_date', False, False, 'date'),
        ('total_amount', False, False, 'decimal'),
        ('status', False, False, 'enum'),
    ], 1180, 100, 'booking', 310),

    Table('vendor_categories', [
        ('id', True, False, 'bigint'),
        ('name', False, False, 'string'),
        ('commission_rate', False, False, 'decimal'),
        ('is_active', False, False, 'bool'),
    ], 420, 480, 'vendor', 280),

    Table('transactions', [
        ('id', True, False, 'bigint'),
        ('transaction_code', False, False, 'string'),
        ('user_id', False, True, 'FK → users'),
        ('payable_type', False, False, 'morph'),
        ('payable_id', False, False, 'morph'),
        ('type', False, False, 'enum'),
        ('amount', False, False, 'decimal'),
        ('status', False, False, 'enum'),
    ], 1180, 420, 'finance', 310),

    Table('payouts', [
        ('id', True, False, 'bigint'),
        ('payout_code', False, False, 'string'),
        ('vendor_id', False, True, 'FK → vendors'),
        ('amount', False, False, 'decimal'),
        ('net_amount', False, False, 'decimal'),
        ('status', False, False, 'enum'),
    ], 820, 480, 'finance', 280),

    Table('commission_settings', [
        ('id', True, False, 'bigint'),
        ('vendor_category_id', False, True, 'FK → categories'),
        ('rate_percent', False, False, 'decimal'),
        ('is_active', False, False, 'bool'),
    ], 50, 480, 'finance', 280),

    Table('invitation_templates', [
        ('id', True, False, 'bigint'),
        ('invitation_template_category_id', False, True, 'FK'),
        ('name', False, False, 'string'),
        ('price', False, False, 'decimal'),
        ('is_premium', False, False, 'bool'),
    ], 1570, 100, 'invitation', 310),

    Table('invitation_orders', [
        ('id', True, False, 'bigint'),
        ('order_code', False, False, 'string'),
        ('user_id', False, True, 'FK → users'),
        ('invitation_template_id', False, True, 'FK'),
        ('bride_name', False, False, 'string'),
        ('groom_name', False, False, 'string'),
        ('status', False, False, 'enum'),
    ], 1570, 380, 'invitation', 310),

    Table('invitations', [
        ('id', True, False, 'bigint'),
        ('invitation_order_id', False, True, 'FK'),
        ('user_id', False, True, 'FK → users'),
        ('slug', False, False, 'string'),
        ('views_count', False, False, 'int'),
        ('rsvp_yes', False, False, 'int'),
        ('is_published', False, False, 'bool'),
    ], 1960, 100, 'invitation', 300),

    Table('invitation_rsvps', [
        ('id', True, False, 'bigint'),
        ('invitation_id', False, True, 'FK → invitations'),
        ('guest_name', False, False, 'string'),
        ('attendance', False, False, 'enum'),
        ('guest_count', False, False, 'int'),
    ], 1960, 380, 'invitation', 300),

    Table('blogs', [
        ('id', True, False, 'bigint'),
        ('user_id', False, True, 'FK → users'),
        ('title', False, False, 'string'),
        ('status', False, False, 'enum'),
    ], 50, 800, 'content', 280),

    Table('testimonials', [
        ('id', True, False, 'bigint'),
        ('name', False, False, 'string'),
        ('rating', False, False, 'int'),
        ('is_active', False, False, 'bool'),
    ], 400, 800, 'content', 280),

    Table('banners', [
        ('id', True, False, 'bigint'),
        ('title', False, False, 'string'),
        ('position', False, False, 'string'),
        ('is_active', False, False, 'bool'),
    ], 750, 800, 'content', 280),

    Table('faqs', [
        ('id', True, False, 'bigint'),
        ('question', False, False, 'string'),
        ('category', False, False, 'string'),
        ('is_active', False, False, 'bool'),
    ], 1100, 800, 'content', 280),

    Table('settings', [
        ('id', True, False, 'bigint'),
        ('group', False, False, 'string'),
        ('key', False, False, 'string (unique)'),
        ('value', False, False, 'text'),
        ('type', False, False, 'string'),
    ], 1450, 800, 'system', 280),

    Table('media', [
        ('id', True, False, 'bigint'),
        ('model_type', False, False, 'morph'),
        ('model_id', False, False, 'morph'),
        ('collection_name', False, False, 'string'),
        ('file_name', False, False, 'string'),
    ], 1800, 700, 'system', 280),

    Table('activity_logs', [
        ('id', True, False, 'bigint'),
        ('user_id', False, True, 'FK → users'),
        ('action', False, False, 'string'),
        ('subject_type', False, False, 'morph'),
        ('subject_id', False, False, 'morph'),
    ], 1800, 920, 'system', 280),
]

for t in tables:
    t.draw()

draw_arrow(330, 170, 420, 170, CREAM)
draw.text((350, 150), "1:1", fill=GRAY, font=font_sm)

draw_arrow(740, 170, 820, 170, CREAM)
draw.text((755, 150), "1:N", fill=GRAY, font=font_sm)

draw_arrow(1100, 170, 1180, 170, CREAM)
draw.text((1115, 150), "1:N", fill=GRAY, font=font_sm)

draw_arrow(560, 200, 560, 480, CREAM)

draw_arrow(920, 190, 1180, 420, '#2563EB')
draw.text((1010, 290), "payable (morph)", fill=GRAY, font=font_sm)

draw_arrow(950, 190, 950, 480, '#16A34A')

draw_arrow(1490, 170, 1570, 170, '#8B5CF6')

draw_arrow(1720, 210, 1960, 210, '#8B5CF6')
draw.text((1800, 195), "1:1", fill=GRAY, font=font_sm)

draw_arrow(1880, 250, 1880, 380, '#8B5CF6')
draw.text((1890, 310), "1:N", fill=GRAY, font=font_sm)

legend_y = 1150
legend_items = [
    ('auth', 'Authentication & Users'),
    ('vendor', 'Vendor System'),
    ('booking', 'Booking System'),
    ('invitation', 'Digital Invitations'),
    ('finance', 'Finance & Payouts'),
    ('content', 'Content Management'),
    ('system', 'System & Media'),
]
draw.rounded_rectangle([(40, legend_y), (W - 40, legend_y + 200)], radius=10, fill=WHITE, outline='#E5E5E5')
draw.text((60, legend_y + 15), "Legend:", fill=BLACK, font=font_bold)
lx = 60
ly = legend_y + 45
for i, (key, label) in enumerate(legend_items):
    if i == 4:
        lx = 60
        ly += 40
    draw.rounded_rectangle([(lx, ly), (lx + 20, ly + 20)], radius=4, fill=TABLE_COLORS[key])
    draw.text((lx + 30, ly + 2), label, fill=DARK, font=font_med)
    lx += 320

draw.text((60, ly + 45), "PK = Primary Key  |  FK = Foreign Key  |  morph = Polymorphic Relation", fill=GRAY, font=font_sm)

output = os.path.join(r"C:\laragon\www\brightdor\screenshots", "erd.png")
img.save(output, quality=95)
print(f"ERD saved to: {output}")
