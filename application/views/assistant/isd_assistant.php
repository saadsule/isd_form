<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/assistant/isd_assistant.php
//  ISD AI Assistant — powered by Claude API
//  Place your Anthropic API key in config or environment variable
// ══════════════════════════════════════════════════════════════════════════

// ── Handle AJAX API call ──────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'chat') {
    header('Content-Type: application/json');

    $user_message  = isset($_POST['message'])  ? trim($_POST['message'])  : '';
    $history_raw   = isset($_POST['history'])  ? $_POST['history']        : '[]';
    $history       = json_decode($history_raw, true);
    if (!is_array($history)) $history = [];

    if (empty($user_message)) {
        echo json_encode(['error' => 'Empty message']);
        exit;
    }

    // ── System prompt — full ISD knowledge ───────────────────────────────
    $system_prompt = <<<'SYSTEM'
You are the ISD Assistant — an intelligent help agent built into the Integrated Service Delivery (ISD) system for North Waziristan, Pakistan. You help data entry personnel, supervisors, and health workers use the system correctly.

You have deep knowledge of two forms:
1. Child Health Record Form
2. OPD / MNCH Patient Form

== YOUR PERSONALITY ==
- Respond in the same language the user writes in (Urdu, Roman Urdu, or English)
- Be warm, clear, and direct — like a helpful senior colleague
- Never say "I don't know" — always guide them to next steps
- When user asks WHERE to find data, give them exact navigation steps in the system

== SYSTEM NAVIGATION GUIDE ==
The ISD system is at: https://isdkp.com

LOGIN: https://isdkp.com/auth/login
- Username and password provided by district coordinator
- If login fails: contact your UCMO or district EPI focal person

CHILD HEALTH FORM (data entry): https://isdkp.com/forms/child_health
- Used for child vaccination records
- Fill QR Code first, then all mandatory fields

OPD/MNCH FORM (data entry): https://isdkp.com/forms/opd_mnch
- Used for maternal and child health visits
- Select Visit Type (OPD or MNCH) FIRST

VIEW CHILD HEALTH RECORDS: https://isdkp.com/forms/child_health_report
- Search by QR code, UC, date range
- Click any record to view full form details

VIEW OPD/MNCH RECORDS: https://isdkp.com/forms/opd_report
- Search and filter by date, UC, district
- Export available for supervisors

REPORTS SECTION:
- Vaccination Report: Dashboard > Reports > Vaccination
- UC-wise data: Dashboard > Reports > UC Wise Report
- Follow-up status: Dashboard > Reports > Follow Up Status
- QR Code history: Dashboard > Reports > QR History Search

== CHILD HEALTH FORM — VALIDATION RULES ==

QR CODE:
- Must be unique — no duplicates allowed
- For existing child: enter their existing QR code
- For new child: enter new QR code to create record
- If duplicate found: STOP, verify with vaccinator, then enter

DATE:
- Cannot be future date — only current or past
- Format: MM/DD/YYYY (e.g. 04/13/2025)
- Mandatory — cannot be blank

DISTRICT: Select North Waziristan from dropdown. Mandatory.

UC (Union Council): Select correct UC from dropdown. Mandatory.

HF/VILLAGE: Full name of village or health facility. Mandatory.

VACCINATOR NAME: Full name, letters only — no numbers. Mandatory.

PATIENT NAME: Child's full name. Letters only, no numbers. Mandatory.

FATHER/HUSBAND NAME: Letters only, no numbers. Mandatory.

CLIENT TYPE: Select New or Follow-up. Mandatory.

SERVICE TYPE: Select Fixed Site or Outreach. Mandatory.

DATE OF BIRTH: Optional but if entered must match age. Format MM/DD/YYYY.

AGE: Must match Date of Birth. Auto-calculated where possible. Mandatory.

AGE GROUP: Must match actual age. Critical — antigen selection is validated against this.
- Less than 1 Year
- 1-2 Years
- 2-5 Years

GENDER: Male or Female. Mandatory.

MARITAL STATUS: Only required if age is above 12 years. Leave blank for age 12 or below.

PREGNANCY STATUS: Only for females aged 15-49 years. Select Pregnant or Non-Pregnant.

DISABILITY: Yes or No. Optional.

PLAY & LEARNING KIT: Yes or No. Optional.

NUTRITION PACKAGE: Yes or No. Optional.

ANTIGEN SELECTION RULES (CRITICAL):
- Under 1 Year (Q18): BCG, OPV0, HepB, PENTA1, PCV1, ROTA1, IPV
  → ONLY mark if Age Group is Less than 1 Year
- 1-2 Years (Q19): PENTA I, PCV I, OPV, IPV, MR
  → ONLY valid when Age Group is 1-2 Years
- 2-5 Years (Q20): OPV, PENTA, PCV
  → ONLY valid when Age Group is 2-5 Years
- WRONG age group + antigen = PROBLEMATIC form. Verify with vaccinator.

CHILD VACCINATED THIS VISIT: Yes or No. If No — reason must be selected.

REASON NOT VACCINATED: Required if child not vaccinated. If Refusal selected — specify refusal type.

TT VACCINATION:
- If YES: select Dose Number (1st-5th) and TD Card Issued. SKIP Refused/Complete/Not Due.
- If NO: select at least one reason: Refused / Complete Schedule / Dose Not Due.

== OPD/MNCH FORM — VALIDATION RULES ==

VISIT TYPE: Select OPD or MNCH FIRST before any other field. Mandatory.

QR CODE: Optional. Enter existing patient QR to link record. Must be unique if entered.

DATE: Cannot be future. Format MM/DD/YYYY. Mandatory.

ANC CARD #: Required ONLY if Visit Type is MNCH. Leave blank for OPD.

CLIENT TYPE: New Client or Follow-up. Mandatory.

DISTRICT: North Waziristan. Mandatory.

UC: Correct UC from dropdown. Mandatory.

HT/LHV NAME: Full name of health worker. Letters only. Mandatory.

PATIENT NAME: Full name. Letters only. Mandatory.

FATHER/HUSBAND NAME: Letters only. Mandatory.

AGE GROUP: Select 0-14Y, 15-49Y, or 50Y+. Must match patient age. Determines active sections. Mandatory.

DISABILITY: Yes or No. Mandatory.

MARITAL STATUS: Married or Unmarried. Optional.

PREGNANCY STATUS: Only for females 15-49 years. Pregnant or Non-Pregnant.

SECTION 18 — ANTENATAL CARE (MNCH + Pregnant only):
- Trimester: 1st, 2nd, or 3rd. Only for MNCH + Pregnancy=Pregnant + Age Group 15-49Y.
- Visit Number: 1st, 2nd, 3rd, or 4th.
- Any Complication: Yes or No. If Yes — at least one complication type must be selected.
- Complications: Anemia, Gestational Hypertension, Pre-Eclampsia, Eclampsia, Gestational Diabetes, Asthma, Type 2 Diabetes, UTI, Hepatitis, Obstetric, Pulmonary Embolism, Deep Vein Thrombosis.
- EDD: Must be FUTURE date. Format MM/DD/YYYY.

SECTION 19 — VACCINATION SERVICES (females 15-49Y only):
- Tetanus Vaccine: ONLY for females 15-49Y. If other age group — INVALID.
- If YES: select Dose Number (1st-5th) and TD Card Issued. Skip other questions.
- If NO: select reason — Refused / Complete TT Schedule / Dose Not Due.

SECTION 20 — DIAGNOSIS:
- At least one diagnosis must be selected.
- If 'Other' selected — 'If Other Explain' field MUST be filled.

PRESCRIBED MEDICINES ISSUED: Yes or No. Mandatory.

KITS ISSUED: Optional — Clean Delivery Kit, Hygiene Kit, Learning Kit.

== VALIDATION ISSUE PROTOCOL ==
If ANY validation problem found in a record:
1. STOP — do not enter data
2. IDENTIFY — note which field has the problem
3. VERIFY — contact vaccinator or district/local person
4. ENTER — only after verification and confirmation

== FINAL CHECKLIST BEFORE SAVING ==
Child Health:
✓ All mandatory fields completed
✓ Date of Birth matches Age
✓ Antigens match Age Group
✓ At least one antigen if child was vaccinated
✓ Reason provided if child NOT vaccinated
✓ No numbers in name fields
✓ QR Code unique
✓ Date not future

OPD/MNCH:
✓ Visit Type selected first
✓ ANC Card only for MNCH
✓ Section 18 only for MNCH + Pregnant
✓ Section 19 only for females 15-49Y
✓ EDD is future date
✓ If Complication=Yes then complication type selected
✓ If Diagnosis=Other then explain filled
✓ At least one diagnosis in Section 20

== HOW TO FIND SPECIFIC DATA ==
- Find a child's vaccination history: Go to Reports > QR History Search > enter QR code
- Find all vaccinations in a UC this month: Go to Reports > Vaccination Report > filter by UC and month
- Find follow-up status: Go to Reports > Follow Up Status
- Find forms by date: Go to View Child Health / View OPD-MNCH > filter by date range
- Find anomalies or errors: Go to Reports > Data Anomalies
- Check UC-wise counts: Go to Reports > UC Wise Report

== CAPACITY BUILDING GUIDANCE ==
If someone asks how to improve their skills or learn the system:
- Validation rules document is available from supervisor
- Practice on test records before live data
- For any doubt: Stop, Verify, then Enter
- Contact UCMO or district EPI focal person for field verification issues
- Refresher training sessions are conducted fortnightly by the ISD team

Always be helpful, specific, and guide the user step by step.
SYSTEM;

    // ── Build messages array ──────────────────────────────────────────────
    $messages = [];
    foreach ($history as $h) {
        if (isset($h['role']) && isset($h['content'])) {
            $messages[] = ['role' => $h['role'], 'content' => $h['content']];
        }
    }
    $messages[] = ['role' => 'user', 'content' => $user_message];

    // ── Call Anthropic API ────────────────────────────────────────────────
$api_key = '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'; // paste your OpenRouter key here

$payload = json_encode([
    'model'    => 'openrouter/auto',
    'messages' => array_merge(
        [['role' => 'system', 'content' => $system_prompt]],
        $messages
    ),
]);

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'HTTP-Referer: https://isdkp.com',
        'X-Title: ISD Assistant',
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['error' => 'Connection error: ' . $err]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['choices'][0]['message']['content'])) {
    echo json_encode(['reply' => $data['choices'][0]['message']['content']]);
} else {
    echo json_encode(['error' => 'API error', 'raw' => $data, 'response_raw' => $response]);
}
exit;
}
?>

<style>
/* ── ISD Assistant Styles ── */
.asst-page { background: #f4f6f9; min-height: 80vh; }

.asst-header {
    background: linear-gradient(135deg, #0f1c3f 0%, #1a3a6e 60%, #2d5f9e 100%);
    border-radius: 14px; padding: 20px 28px; margin-bottom: 22px; color: #fff;
    display: flex; align-items: center; gap: 18px;
}
.asst-header .icon-wrap {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.asst-header .icon-wrap i { font-size: 26px; color: #fff; }
.asst-header h2 { font-size: 20px; font-weight: 700; margin: 0 0 4px; }
.asst-header p  { font-size: 13px; opacity: .75; margin: 0; }
.asst-header .status-pill {
    margin-left: auto; background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px; padding: 5px 14px; font-size: 12px;
    display: flex; align-items: center; gap: 7px; white-space: nowrap;
}
.asst-header .status-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #4ade80; box-shadow: 0 0 6px #4ade80;
}

.asst-body { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }
@media(max-width:900px){ .asst-body { grid-template-columns: 1fr; } }

/* Chat panel */
.chat-panel {
    background: #fff; border-radius: 14px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    display: flex; flex-direction: column; overflow: hidden;
}
.chat-topbar {
    padding: 14px 20px; border-bottom: 1px solid #edf0f4;
    display: flex; align-items: center; gap: 10px;
}
.chat-topbar i { font-size: 18px; color: #1a3a6e; }
.chat-topbar span { font-size: 14px; font-weight: 600; color: #2c3e50; }
.chat-topbar .clr-btn {
    margin-left: auto; font-size: 12px; color: #888;
    background: none; border: none; cursor: pointer; padding: 4px 8px;
    border-radius: 6px;
}
.chat-topbar .clr-btn:hover { background: #f0f0f0; color: #333; }

.chat-messages {
    flex: 1; padding: 20px; overflow-y: auto;
    display: flex; flex-direction: column; gap: 14px;
    min-height: 420px; max-height: 520px;
}

/* Messages */
.msg-row { display: flex; gap: 10px; align-items: flex-start; }
.msg-row.user { flex-direction: row-reverse; }

.msg-av {
    width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 15px;
}
.msg-av.bot { background: linear-gradient(135deg,#0f1c3f,#2d5f9e); color: #fff; }
.msg-av.user { background: #e8f0fe; color: #1a3a6e; }

.msg-bubble {
    max-width: 75%; padding: 11px 15px; font-size: 13.5px; line-height: 1.65;
    border-radius: 4px 14px 14px 14px; color: #2c3e50;
    background: #f5f7fb; border: 1px solid #edf0f4;
}
.msg-row.user .msg-bubble {
    background: #0f1c3f; color: #fff; border-color: #0f1c3f;
    border-radius: 14px 4px 14px 14px;
}
.msg-bubble a { color: #2980b9; }
.msg-row.user .msg-bubble a { color: #90caf9; }
.msg-bubble .nav-step {
    background: rgba(255,255,255,.12); border-radius: 6px;
    padding: 6px 10px; margin-top: 8px; font-size: 12px;
    border-left: 3px solid #4ade80;
}
.msg-row.bot .msg-bubble .nav-step {
    background: #e8f4fd; border-left: 3px solid #2980b9; color: #1a3a6e;
}

.typing-bubble { background: #f5f7fb; border: 1px solid #edf0f4; border-radius: 4px 14px 14px 14px; padding: 12px 16px; }
.typing-dots { display: flex; gap: 5px; }
.typing-dots span {
    width: 7px; height: 7px; border-radius: 50%; background: #aab;
    animation: tdot 1.2s infinite;
}
.typing-dots span:nth-child(2) { animation-delay: .2s; }
.typing-dots span:nth-child(3) { animation-delay: .4s; }
@keyframes tdot { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-6px)} }

/* Quick questions */
.quick-wrap { padding: 12px 20px 0; display: flex; flex-wrap: wrap; gap: 7px; }
.quick-q {
    font-size: 11.5px; padding: 5px 12px; border-radius: 20px;
    border: 1px solid #d0daea; background: #f8f9ff; color: #1a3a6e;
    cursor: pointer; transition: all .15s; white-space: nowrap;
}
.quick-q:hover { background: #1a3a6e; color: #fff; border-color: #1a3a6e; }

/* Input */
.chat-input-area { padding: 14px 20px 18px; border-top: 1px solid #edf0f4; }
.input-row { display: flex; gap: 10px; }
.input-row textarea {
    flex: 1; resize: none; padding: 11px 15px; font-size: 13.5px;
    font-family: inherit; border-radius: 10px; height: 46px;
    border: 1.5px solid #d0daea; color: #2c3e50; background: #fff;
    transition: border .15s; line-height: 1.5; overflow: hidden;
}
.input-row textarea:focus { outline: none; border-color: #1a3a6e; }
.send-btn {
    width: 46px; height: 46px; border-radius: 10px; flex-shrink: 0;
    background: #0f1c3f; color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.send-btn:hover { background: #1a3a6e; }
.send-btn i { font-size: 18px; }
.send-btn:disabled { opacity: .5; cursor: not-allowed; }

/* Sidebar */
.sidebar { display: flex; flex-direction: column; gap: 16px; }

.side-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    overflow: hidden;
}
.side-card-header {
    padding: 13px 16px; border-bottom: 1px solid #edf0f4;
    display: flex; align-items: center; gap: 8px;
}
.side-card-header i { font-size: 16px; color: #1a3a6e; }
.side-card-header span { font-size: 13px; font-weight: 700; color: #2c3e50; }
.side-card-body { padding: 14px 16px; }

/* Quick links */
.qlink {
    display: flex; align-items: center; gap: 10px; padding: 9px 0;
    border-bottom: 1px solid #f0f2f5; text-decoration: none; color: #2c3e50;
    font-size: 12.5px; transition: color .15s;
}
.qlink:last-child { border-bottom: none; padding-bottom: 0; }
.qlink:hover { color: #1a3a6e; }
.qlink i { font-size: 15px; color: #888; width: 18px; }
.qlink:hover i { color: #1a3a6e; }

/* Admin card */
.admin-toggle-btn {
    width: 100%; text-align: left; background: none; border: none;
    padding: 13px 16px; cursor: pointer; display: flex; align-items: center;
    gap: 8px; font-size: 13px; font-weight: 700; color: #2c3e50;
    border-bottom: 1px solid #edf0f4;
}
.admin-toggle-btn i { font-size: 16px; color: #1a3a6e; }
.admin-toggle-btn .chevron { margin-left: auto; font-size: 14px; transition: transform .2s; }
.admin-toggle-btn.open .chevron { transform: rotate(180deg); }
.admin-body { padding: 14px 16px; display: none; }
.admin-body.open { display: block; }
.admin-body input, .admin-body textarea {
    width: 100%; font-size: 12.5px; font-family: inherit;
    padding: 8px 10px; border-radius: 8px;
    border: 1px solid #d0daea; color: #2c3e50; background: #fff;
    margin-bottom: 8px;
}
.admin-body input:focus, .admin-body textarea:focus { outline: none; border-color: #1a3a6e; }
.admin-body textarea { height: 70px; resize: vertical; }
.add-rule-btn {
    width: 100%; padding: 8px; font-size: 12.5px; font-weight: 600;
    border-radius: 8px; background: #0f1c3f; color: #fff; border: none;
    cursor: pointer; transition: background .15s;
}
.add-rule-btn:hover { background: #1a3a6e; }
.saved-rules { margin-top: 10px; display: flex; flex-direction: column; gap: 6px; max-height: 160px; overflow-y: auto; }
.rule-chip {
    background: #f5f7fb; border-radius: 8px; padding: 7px 10px;
    display: flex; justify-content: space-between; align-items: flex-start; gap: 6px;
    font-size: 11.5px; color: #555; border: 1px solid #edf0f4;
}
.rule-chip strong { font-size: 12px; color: #2c3e50; display: block; margin-bottom: 2px; }
.rule-chip .del { background: none; border: none; cursor: pointer; color: #e74c3c; padding: 0; flex-shrink: 0; }
.success-flash { font-size: 11.5px; color: #27ae60; margin-top: 4px; display: none; }
</style>

<div class="page-container">
<div class="main-content asst-page">

<!-- HEADER -->
<div class="asst-header">
    <div class="icon-wrap"><i class="fa fa-robot"></i></div>
    <div>
        <h2>ISD AI Assistant</h2>
        <p>Data entry help &amp; system guidance — Child Health &amp; OPD/MNCH Forms</p>
    </div>
    <div class="status-pill">
        <div class="status-dot"></div> AI Online
    </div>
</div>

<!-- BODY -->
<div class="asst-body">

    <!-- ── CHAT PANEL ── -->
    <div class="chat-panel">
        <div class="chat-topbar">
            <i class="fa fa-comments"></i>
            <span>Ask anything about the ISD system</span>
            <button class="clr-btn" onclick="clearChat()">
                <i class="fa fa-refresh"></i> Clear chat
            </button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <!-- Welcome message -->
            <div class="msg-row bot">
                <div class="msg-av bot"><i class="fa fa-robot"></i></div>
                <div class="msg-bubble">
                    <strong>Assalamu Alaikum!</strong> Main ISD system ka AI assistant hoon.<br><br>
                    Aap mujh se koi bhi sawaal pooch saktay hain — data entry rules, system navigation, form fields, ya validation issues ke baare mein.<br><br>
                    <em>You can also ask in English or Roman Urdu.</em>
                </div>
            </div>
        </div>

        <!-- Quick questions -->
        <div class="quick-wrap" id="quickWrap">
            <button class="quick-q" onclick="askQuick('QR code duplicate ho jaye to kya karna hai?')">QR duplicate error</button>
            <button class="quick-q" onclick="askQuick('Child Health form ke mandatory fields koun se hain?')">Mandatory fields</button>
            <button class="quick-q" onclick="askQuick('Age group aur antigen ka kya relationship hai?')">Antigen + age group</button>
            <button class="quick-q" onclick="askQuick('Vaccination report kahan milega system mein?')">Vaccination report location</button>
            <button class="quick-q" onclick="askQuick('TT vaccine ke liye kya rules hain?')">TT vaccine rules</button>
            <button class="quick-q" onclick="askQuick('Validation issue mile to kya karna chahiye?')">Validation protocol</button>
            <button class="quick-q" onclick="askQuick('OPD aur MNCH mein kya farq hai?')">OPD vs MNCH</button>
            <button class="quick-q" onclick="askQuick('Kisi child ki vaccination history kahan dekhein?')">Child history</button>
        </div>

        <!-- Input -->
        <div class="chat-input-area">
            <div class="input-row">
                <textarea id="userInput"
                    placeholder="Apna sawaal likhein... (English ya Roman Urdu mein)"
                    onkeydown="handleKey(event)"></textarea>
                <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ── SIDEBAR ── -->
    <div class="sidebar">

        <!-- Quick Links -->
        <div class="side-card">
            <div class="side-card-header">
                <i class="fa fa-link"></i>
                <span>System Quick Links</span>
            </div>
            <div class="side-card-body" style="padding:8px 16px;">
                <a class="qlink" href="<?= base_url('forms/child_health') ?>" target="_blank">
                    <i class="fa fa-plus-circle"></i> Child Health Form
                </a>
                <a class="qlink" href="<?= base_url('forms/opd_mnch') ?>" target="_blank">
                    <i class="fa fa-heartbeat"></i> OPD / MNCH Form
                </a>
                <a class="qlink" href="<?= base_url('forms/child_health_report') ?>" target="_blank">
                    <i class="fa fa-list-alt"></i> View Child Health Records
                </a>
                <a class="qlink" href="<?= base_url('forms/opd_report') ?>" target="_blank">
                    <i class="fa fa-file-text"></i> View OPD/MNCH Records
                </a>
                <a class="qlink" href="<?= base_url('reports/vaccination') ?>" target="_blank">
                    <i class="fa fa-syringe"></i> Vaccination Report
                </a>
                <a class="qlink" href="<?= base_url('reports/qr_history_search') ?>" target="_blank">
                    <i class="fa fa-qrcode"></i> QR History Search
                </a>
            </div>
        </div>

        <!-- Tips -->
        <div class="side-card">
            <div class="side-card-header">
                <i class="fa fa-lightbulb-o"></i>
                <span>Key Reminders</span>
            </div>
            <div class="side-card-body" style="font-size:12.5px; color:#555; line-height:1.7;">
                <p style="margin:0 0 8px;">
                    <strong style="color:#e74c3c;">&#9888;</strong>
                    Validation issue mile to — <strong>STOP, VERIFY, then ENTER</strong>
                </p>
                <p style="margin:0 0 8px;">
                    <strong style="color:#2980b9;">&#8594;</strong>
                    Antigen must match child's age group
                </p>
                <p style="margin:0 0 8px;">
                    <strong style="color:#2980b9;">&#8594;</strong>
                    No future dates allowed
                </p>
                <p style="margin:0 0 8px;">
                    <strong style="color:#2980b9;">&#8594;</strong>
                    QR Code must be unique
                </p>
                <p style="margin:0;">
                    <strong style="color:#2980b9;">&#8594;</strong>
                    Name fields: letters only
                </p>
            </div>
        </div>

        <!-- Admin — Add Custom Rules -->
        <div class="side-card">
            <button class="admin-toggle-btn" id="adminToggle" onclick="toggleAdmin()">
                <i class="fa fa-cog"></i>
                Add Custom Rules
                <i class="fa fa-chevron-down chevron"></i>
            </button>
            <div class="admin-body" id="adminBody">
                <input type="text" id="ruleTitle" placeholder="Topic / rule name" />
                <textarea id="ruleContent" placeholder="Guidance text for this rule..."></textarea>
                <button class="add-rule-btn" onclick="addCustomRule()">
                    <i class="fa fa-plus"></i> Add Rule
                </button>
                <div class="success-flash" id="successFlash">Rule saved!</div>
                <div class="saved-rules" id="savedRules"></div>
            </div>
        </div>

    </div>
</div>

</div>

<script>
var chatHistory = [];
var customRules = JSON.parse(localStorage.getItem('isd_custom_rules') || '[]');
var isLoading   = false;

function scrollBottom() {
    var el = document.getElementById('chatMessages');
    el.scrollTop = el.scrollHeight;
}

function addMsg(text, role) {
    var area = document.getElementById('chatMessages');
    var row  = document.createElement('div');
    row.className = 'msg-row ' + role;
    var av = document.createElement('div');
    av.className = 'msg-av ' + role;
    av.innerHTML = role === 'bot'
        ? '<i class="fa fa-robot"></i>'
        : '<i class="fa fa-user"></i>';
    var bubble = document.createElement('div');
    bubble.className = 'msg-bubble';
    bubble.innerHTML = formatText(text);
    row.appendChild(av);
    row.appendChild(bubble);
    area.appendChild(row);
    scrollBottom();
    return row;
}

function formatText(text) {
    text = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');
    text = text.replace(/`(.+?)`/g, '<code style="background:#f0f4ff;padding:1px 5px;border-radius:4px;font-family:monospace;font-size:12px;">$1</code>');
    text = text.replace(/https?:\/\/[^\s]+/g, function(url) {
        return '<a href="'+url+'" target="_blank">'+url+'</a>';
    });
    text = text.replace(/\n/g, '<br>');
    return text;
}

function showTyping() {
    var area = document.getElementById('chatMessages');
    var row  = document.createElement('div');
    row.className = 'msg-row bot'; row.id = 'typing-row';
    var av = document.createElement('div');
    av.className = 'msg-av bot';
    av.innerHTML = '<i class="fa fa-robot"></i>';
    var bubble = document.createElement('div');
    bubble.className = 'typing-bubble';
    bubble.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
    row.appendChild(av); row.appendChild(bubble);
    area.appendChild(row);
    scrollBottom();
}

function removeTyping() {
    var t = document.getElementById('typing-row');
    if (t) t.remove();
}

function sendMessage() {
    if (isLoading) return;
    var input = document.getElementById('userInput');
    var text  = input.value.trim();
    if (!text) return;

    input.value = '';
    addMsg(text, 'user');
    chatHistory.push({ role: 'user', content: text });

    isLoading = true;
    document.getElementById('sendBtn').disabled = true;
    document.getElementById('quickWrap').style.display = 'none';
    showTyping();

    var customContext = '';
    if (customRules.length > 0) {
        customContext = '\n\nAdditional custom rules added by admin:\n';
        customRules.forEach(function(r) {
            customContext += '- ' + r.title + ': ' + r.content + '\n';
        });
    }

    var historyToSend = chatHistory.slice(0, -1);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        removeTyping();
        isLoading = false;
        document.getElementById('sendBtn').disabled = false;
        try {
            var data = JSON.parse(xhr.responseText);
            if (data.reply) {
                var reply = data.reply;
                if (customContext) reply = reply;
                addMsg(reply, 'bot');
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                addMsg('Maafi chahta hoon, abhi kuch technical masla aa gaya. Thodi der baad dobara try karein.', 'bot');
            }
        } catch(e) {
            addMsg('Response parse error. Please try again.', 'bot');
        }
    };
    xhr.onerror = function() {
        removeTyping();
        isLoading = false;
        document.getElementById('sendBtn').disabled = false;
        addMsg('Network error. Please check your connection.', 'bot');
    };

    var payload = 'action=chat'
        + '&message=' + encodeURIComponent(text + (customContext ? '\n\n[Admin context: ' + customContext + ']' : ''))
        + '&history=' + encodeURIComponent(JSON.stringify(historyToSend));
    xhr.send(payload);
}

function askQuick(q) {
    document.getElementById('userInput').value = q;
    sendMessage();
}

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}

function clearChat() {
    chatHistory = [];
    var area = document.getElementById('chatMessages');
    area.innerHTML = '';
    document.getElementById('quickWrap').style.display = 'flex';
    addMsg('Chat cleared. Koi bhi naya sawaal pooch saktay hain!', 'bot');
}

function toggleAdmin() {
    var btn  = document.getElementById('adminToggle');
    var body = document.getElementById('adminBody');
    btn.classList.toggle('open');
    body.classList.toggle('open');
    renderRules();
}

function addCustomRule() {
    var title   = document.getElementById('ruleTitle').value.trim();
    var content = document.getElementById('ruleContent').value.trim();
    if (!title || !content) return;
    customRules.push({ title: title, content: content });
    localStorage.setItem('isd_custom_rules', JSON.stringify(customRules));
    document.getElementById('ruleTitle').value   = '';
    document.getElementById('ruleContent').value = '';
    var flash = document.getElementById('successFlash');
    flash.style.display = 'block';
    setTimeout(function(){ flash.style.display = 'none'; }, 2000);
    renderRules();
}

function deleteRule(i) {
    customRules.splice(i, 1);
    localStorage.setItem('isd_custom_rules', JSON.stringify(customRules));
    renderRules();
}

function renderRules() {
    var list = document.getElementById('savedRules');
    list.innerHTML = '';
    if (customRules.length === 0) {
        list.innerHTML = '<p style="font-size:12px;color:#aaa;margin-top:8px;">No custom rules yet.</p>';
        return;
    }
    customRules.forEach(function(r, i) {
        var chip = document.createElement('div');
        chip.className = 'rule-chip';
        chip.innerHTML = '<div><strong>' + r.title + '</strong>'
            + r.content.substring(0,70) + (r.content.length>70?'...':'')
            + '</div><button class="del" onclick="deleteRule('+i+')" title="Delete"><i class="fa fa-times"></i></button>';
        list.appendChild(chip);
    });
}
</script>