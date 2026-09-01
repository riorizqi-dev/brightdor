"""Generate a draw.io (mxfile XML) ERD for BrightDor.

22 domain tables (including the 3 new ones: audit_logs, notifications,
vendor_documents). The core marketplace chain
users -> vendors -> services -> bookings -> transactions/payouts is
highlighted with thick cream edges.

Run:  python scripts/generate_erd_drawio.py
Out:  docs/brightdor-erd.drawio
"""
import html
import os

CREAM = "#C4A574"
GROUP_COLORS = {
    "auth": "#141414",
    "vendor": "#C4A574",
    "booking": "#2563EB",
    "invitation": "#8B5CF6",
    "finance": "#16A34A",
    "content": "#EA580C",
    "system": "#64748B",
}

# (name, group, [(col, key) where key in {'', 'PK', 'FK', 'UQ', 'MORPH'}])
TABLES = [
    ("users", "auth", [
        ("id", "PK"), ("name", ""), ("email", "UQ"), ("phone", ""),
        ("user_type", ""), ("status", ""), ("password", ""),
    ]),
    ("vendor_categories", "vendor", [
        ("id", "PK"), ("name", ""), ("slug", "UQ"), ("commission_rate", ""),
    ]),
    ("vendors", "vendor", [
        ("id", "PK"), ("user_id", "FK"), ("vendor_category_id", "FK"),
        ("business_name", ""), ("slug", "UQ"), ("status", ""),
        ("is_verified", ""), ("rating_avg", ""),
    ]),
    ("services", "vendor", [
        ("id", "PK"), ("vendor_id", "FK"), ("vendor_category_id", "FK"),
        ("name", ""), ("price", ""), ("status", ""), ("is_featured", ""),
    ]),
    ("bookings", "booking", [
        ("id", "PK"), ("booking_code", "UQ"), ("user_id", "FK"),
        ("vendor_id", "FK"), ("service_id", "FK"), ("event_date", ""),
        ("total_amount", ""), ("commission_amount", ""), ("status", ""),
    ]),
    ("reviews", "booking", [
        ("id", "PK"), ("booking_id", "FK"), ("user_id", "FK"),
        ("vendor_id", "FK"), ("rating", ""), ("comment", ""),
    ]),
    ("transactions", "finance", [
        ("id", "PK"), ("transaction_code", "UQ"), ("user_id", "FK"),
        ("payable_type", "MORPH"), ("payable_id", "MORPH"), ("type", ""),
        ("amount", ""), ("fee", ""), ("net_amount", ""), ("status", ""),
    ]),
    ("commission_settings", "finance", [
        ("id", "PK"), ("vendor_category_id", "FK"), ("rate_percent", ""),
        ("rate_fixed", ""), ("is_active", ""),
    ]),
    ("payouts", "finance", [
        ("id", "PK"), ("payout_code", "UQ"), ("vendor_id", "FK"),
        ("amount", ""), ("status", ""), ("bank_account", ""),
    ]),
    ("vendor_documents", "vendor", [
        ("id", "PK"), ("vendor_id", "FK"), ("document_type", ""),
        ("file_path", ""), ("status", ""), ("reviewed_at", ""),
    ]),
    ("invitation_template_categories", "invitation", [
        ("id", "PK"), ("name", ""), ("slug", "UQ"),
    ]),
    ("invitation_templates", "invitation", [
        ("id", "PK"), ("category_id", "FK"), ("name", ""), ("price", ""),
        ("is_featured", ""), ("status", ""),
    ]),
    ("invitation_orders", "invitation", [
        ("id", "PK"), ("user_id", "FK"), ("template_id", "FK"),
        ("price_paid", ""), ("status", ""),
    ]),
    ("invitations", "invitation", [
        ("id", "PK"), ("order_id", "FK"), ("slug", "UQ"), ("title", ""),
        ("event_date", ""), ("is_published", ""), ("views", ""),
    ]),
    ("invitation_rsvps", "invitation", [
        ("id", "PK"), ("invitation_id", "FK"), ("guest_name", ""),
        ("attendance", ""), ("message", ""),
    ]),
    ("blogs", "content", [
        ("id", "PK"), ("user_id", "FK"), ("title", ""), ("slug", "UQ"),
        ("status", ""), ("published_at", ""),
    ]),
    ("testimonials", "content", [
        ("id", "PK"), ("name", ""), ("rating", ""), ("is_active", ""),
    ]),
    ("banners", "content", [
        ("id", "PK"), ("title", ""), ("position", ""), ("is_active", ""),
    ]),
    ("settings", "system", [
        ("id", "PK"), ("group", ""), ("key", "UQ"), ("value", ""), ("type", ""),
    ]),
    ("activity_logs", "system", [
        ("id", "PK"), ("user_id", "FK"), ("action", ""),
        ("subject_type", "MORPH"), ("subject_id", "MORPH"),
    ]),
    ("audit_logs", "system", [
        ("id", "PK"), ("user_id", "FK"), ("action", ""), ("model", ""),
        ("old_values", ""), ("new_values", ""), ("ip_address", ""),
    ]),
    ("notifications", "system", [
        ("id", "PK"), ("type", ""), ("notifiable_type", "MORPH"),
        ("notifiable_id", "MORPH"), ("data", ""), ("read_at", ""),
    ]),
]

# (src_table, src_col, dst_table, label, is_highlight)
RELATIONS = [
    ("vendors", "user_id", "users", "1:1", True),
    ("vendors", "vendor_category_id", "vendor_categories", "N:1", False),
    ("services", "vendor_id", "vendors", "1:N", True),
    ("services", "vendor_category_id", "vendor_categories", "N:1", False),
    ("bookings", "user_id", "users", "1:N", True),
    ("bookings", "vendor_id", "vendors", "1:N", True),
    ("bookings", "service_id", "services", "N:1", True),
    ("transactions", "user_id", "users", "1:N", True),
    ("transactions", "payable_id", "bookings", "morph", True),
    ("transactions", "payable_id", "invitation_orders", "morph", False),
    ("commission_settings", "vendor_category_id", "vendor_categories", "N:1", False),
    ("payouts", "vendor_id", "vendors", "1:N", True),
    ("vendor_documents", "vendor_id", "vendors", "1:N", False),
    ("reviews", "booking_id", "bookings", "1:1", False),
    ("invitation_templates", "category_id", "invitation_template_categories", "N:1", False),
    ("invitation_orders", "user_id", "users", "1:N", False),
    ("invitation_orders", "template_id", "invitation_templates", "N:1", False),
    ("invitations", "order_id", "invitation_orders", "1:1", False),
    ("invitation_rsvps", "invitation_id", "invitations", "1:N", False),
    ("blogs", "user_id", "users", "N:1", False),
    ("activity_logs", "user_id", "users", "N:1", False),
    ("audit_logs", "user_id", "users", "N:1", False),
]

POSITIONS = {
    "users": (40, 320),
    "vendor_categories": (40, 60),
    "vendors": (340, 320),
    "vendor_documents": (340, 620),
    "services": (640, 320),
    "bookings": (940, 320),
    "reviews": (940, 640),
    "transactions": (1240, 200),
    "payouts": (640, 620),
    "commission_settings": (40, 620),
    "invitation_template_categories": (1240, 60),
    "invitation_templates": (1540, 60),
    "invitation_orders": (1540, 320),
    "invitations": (1540, 600),
    "invitation_rsvps": (1840, 600),
    "blogs": (1840, 60),
    "testimonials": (1840, 260),
    "banners": (1240, 480),
    "settings": (2140, 60),
    "activity_logs": (2140, 320),
    "audit_logs": (2140, 540),
    "notifications": (1840, 440),
}

TABLE_W = 260
ROW_H = 26
HEADER_H = 36


def esc(s):
    return html.escape(s, quote=True)


def table_html(name, group, cols):
    color = GROUP_COLORS[group]
    new_badge = "  &#9733;" if name in ("audit_logs", "notifications", "vendor_documents") else ""
    r = [f'<tr><td style="background:{color};color:#ffffff;font-weight:bold;'
         f'padding:6px 8px;font-size:13px;border:1px solid {color};text-align:left;">'
         f'{esc(name)}{new_badge}</td></tr>']
    for col, key in cols:
        badge = ""
        if key == "PK":
            badge = '<span style="color:#B45309;font-weight:bold;">PK</span> '
        elif key == "FK":
            badge = '<span style="color:#2563EB;font-weight:bold;">FK</span> '
        elif key == "UQ":
            badge = '<span style="color:#047857;font-weight:bold;">UQ</span> '
        elif key == "MORPH":
            badge = '<span style="color:#7C3AED;font-weight:bold;">MP</span> '
        r.append(f'<tr><td style="padding:3px 8px;font-size:11px;color:#27272a;'
                 f'border:1px solid #e4e4e7;text-align:left;background:#ffffff;">'
                 f'{badge}{esc(col)}</td></tr>')
    return ('<table cellspacing="0" cellpadding="0" '
            f'style="border-collapse:collapse;width:100%;">{"".join(r)}</table>')


cells = []
cells.append('<mxCell id="0" />')
cells.append('<mxCell id="1" parent="0" />')

cells.append(
    '<mxCell id="title" value="BrightDor &amp;mdash; Entity Relationship Diagram '
    '(22 domain tables, Laravel 11 + Filament v5)" '
    'style="text;html=1;fontSize=20;fontStyle=1;fontColor=#141414;align=left;" '
    'vertex="1" parent="1"><mxGeometry x="40" y="-40" width="900" height="40" '
    'as="geometry" /></mxCell>'
)

legend_rows = "".join(
    f'<span style="color:{c};">&#9632;</span> {g}&nbsp;&nbsp;&nbsp;'
    for g, c in [
        ("Authentication", GROUP_COLORS["auth"]),
        ("Vendor System", GROUP_COLORS["vendor"]),
        ("Booking", GROUP_COLORS["booking"]),
        ("Invitation", GROUP_COLORS["invitation"]),
        ("Finance", GROUP_COLORS["finance"]),
        ("Content/CMS", GROUP_COLORS["content"]),
        ("System", GROUP_COLORS["system"]),
    ]
)
cells.append(
    f'<mxCell id="legend" value="{esc(legend_rows + "<br/>"
    "<b>PK</b> primary &nbsp; <b>FK</b> foreign &nbsp; <b>UQ</b> unique &nbsp; "
    "<b>MP</b> polymorphic &nbsp; &#9733; new table (Task 2) &nbsp; "
    "tebal krem = relasi utama (users-&gt;vendors-&gt;services-&gt;bookings-&gt;"
    "transactions/payouts)")}" '
    'style="text;html=1;fontSize=11;fontColor=#3f3f46;align=left;verticalAlign=top;'
    'spacing=8;fillColor=#ffffff;strokeColor=#d4d4d8;rounded=1;" '
    'vertex="1" parent="1"><mxGeometry x="40" y="10" width="1100" height="60" '
    'as="geometry" /></mxCell>'
)

for name, group, cols in TABLES:
    h = HEADER_H + ROW_H * len(cols) + 8
    x, y = POSITIONS[name]
    style = (
        "shape=table;html=1;whiteSpace=wrap;childLayout=none;collapsible=0;"
        "fillColor=#ffffff;strokeColor=" + GROUP_COLORS[group] +
        ";spacing=0;overflow=hidden;verticalAlign=top;"
    )
    cells.append(
        f'<mxCell id="{name}" value="{esc(table_html(name, group, cols))}" '
        f'style="{style}" vertex="1" parent="1">'
        f'<mxGeometry x="{x}" y="{y}" width="{TABLE_W}" height="{h}" '
        'as="geometry" /></mxCell>'
    )

for i, (src, srccol, dst, label, hi) in enumerate(RELATIONS):
    stroke = CREAM if hi else "#a1a1aa"
    width = 3 if hi else 1
    dashed = "dashed=1;" if "morph" in label else ""
    style = (
        "edgeStyle=entityRelationEdgeStyle;rounded=0;html=1;endArrow=ERmany;"
        "startArrow=ERone;exitX=1;exitY=0.5;entryX=0;entryY=0.5;"
        f"fontColor={'#141414' if hi else '#71717a'};fontSize=10;fontStyle=1;"
        f"strokeColor={stroke};strokeWidth={width};{dashed}"
    )
    cells.append(
        f'<mxCell id="e{i}" value="{esc(label)}" style="{style}" edge="1" '
        f'parent="1" source="{src}" target="{dst}">'
        '<mxGeometry relative="1" as="geometry" /></mxCell>'
    )

xml = (
    '<mxfile host="app.diagrams.net" type="device">'
    '<diagram id="brightdor-erd" name="BrightDor ERD">'
    '<mxGraphModel dx="1400" dy="900" grid="1" gridSize="10" guides="1" '
    'tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" '
    'pageWidth="2400" pageHeight="1400" math="0" shadow="0">'
    '<root>'
    + "".join(cells) +
    '</root></mxGraphModel></diagram></mxfile>'
)

here = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
out = os.path.join(here, "docs", "brightdor-erd.drawio")
with open(out, "w", encoding="utf-8") as f:
    f.write(xml)
print(f"Wrote {out} with {len(TABLES)} tables and {len(RELATIONS)} relations")

