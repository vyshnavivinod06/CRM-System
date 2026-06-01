<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Contacts</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f5f7fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        main {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        h1, h2 {
            margin: 0;
            letter-spacing: 0;
        }

        h1 {
            font-size: 30px;
            line-height: 1.15;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 14px;
        }

        .muted {
            margin: 8px 0 0;
            color: #5d687a;
        }

        .stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .stat, section {
            border: 1px solid #dce2ed;
            background: #fff;
            border-radius: 8px;
        }

        .stat {
            min-width: 112px;
            padding: 10px 12px;
        }

        .stat strong {
            display: block;
            font-size: 22px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        section {
            padding: 18px;
            overflow: hidden;
        }

        label {
            display: grid;
            gap: 6px;
            color: #354054;
            font-size: 13px;
            font-weight: 650;
        }

        form {
            display: grid;
            gap: 12px;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        input {
            width: 100%;
            border: 1px solid #ccd5e3;
            border-radius: 6px;
            padding: 10px 11px;
            font: inherit;
            color: #172033;
        }

        button {
            justify-self: start;
            border: 0;
            border-radius: 6px;
            padding: 10px 14px;
            background: #176b87;
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #e6ebf2;
            vertical-align: top;
        }

        th {
            color: #506078;
            font-size: 12px;
            text-transform: uppercase;
        }

        .notice {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #e8f8ef;
            border: 1px solid #bfe6cf;
            color: #176137;
        }

        .errors {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #fff2f2;
            border: 1px solid #f0c6c6;
            color: #9c2d2d;
        }

        @media (max-width: 760px) {
            header, .grid, .fields {
                grid-template-columns: 1fr;
                display: grid;
            }

            header {
                align-items: start;
            }

            table {
                min-width: 680px;
            }
        }
    </style>
</head>
<body>
<main>
    <header>
        <div>
            <h1>CRM Contacts</h1>
            <p class="muted">Create contacts from accounts and leads, then inspect the generated records.</p>
        </div>
        <div class="stats">
            <div class="stat"><strong>{{ $accounts->count() }}</strong>Accounts</div>
            <div class="stat"><strong>{{ $leads->count() }}</strong>Leads</div>
            <div class="stat"><strong>{{ $contacts->count() }}</strong>Contacts</div>
        </div>
    </header>

    @if (session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid">
        <section>
            <h2>Create Account</h2>
            <form method="POST" action="/crm/accounts">
                @csrf
                <div class="fields">
                    <label>Company name <input name="name" value="{{ old('name') }}" required></label>
                    <label>Email <input type="email" name="email" value="{{ old('email') }}"></label>
                    <label>First name <input name="first_name" value="{{ old('first_name') }}"></label>
                    <label>Last name <input name="last_name" value="{{ old('last_name') }}"></label>
                    <label>Phone <input name="phone" value="{{ old('phone') }}"></label>
                </div>
                <button type="submit">Create account</button>
            </form>
        </section>

        <section>
            <h2>Create Lead</h2>
            <form method="POST" action="/crm/leads">
                @csrf
                <div class="fields">
                    <label>First name <input name="first_name" value="{{ old('first_name') }}"></label>
                    <label>Last name <input name="last_name" value="{{ old('last_name') }}"></label>
                    <label>Email <input type="email" name="email" value="{{ old('email') }}"></label>
                    <label>Company <input name="company_name" value="{{ old('company_name') }}"></label>
                    <label>Status <input name="status" value="{{ old('status', 'new') }}"></label>
                    <label>Phone <input name="phone" value="{{ old('phone') }}"></label>
                </div>
                <button type="submit">Create lead</button>
            </form>
        </section>
    </div>

    <section>
        <h2>Contacts</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Source</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ trim($contact->first_name.' '.$contact->last_name) ?: 'N/A' }}</td>
                    <td>{{ $contact->email ?? 'N/A' }}</td>
                    <td>{{ $contact->phone ?? 'N/A' }}</td>
                    <td>{{ $contact->company_name ?? 'N/A' }}</td>
                    <td>{{ class_basename($contact->source_type) }} #{{ $contact->source_id }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No contacts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
