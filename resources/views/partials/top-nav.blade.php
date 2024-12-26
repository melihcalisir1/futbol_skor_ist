<style>
    .top-nav {
        background-color: #1f1f1f;
        border-bottom: 1px solid #333;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-nav a {
        color: white;
        margin-right: 10px;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
    }

    .top-nav a:hover {
        background-color: #ffcc00;
        color: #1f1f1f;
    }

    .top-nav a.active {
        background-color: #ffcc00;
        color: #1f1f1f;
    }

    .top-nav form {
        display: flex;
        align-items: center;
    }

    .top-nav input[type="date"] {
        color: #333;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 6px 12px;
        margin-right: 10px;
        font-size: 14px;
    }

    .top-nav button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        cursor: pointer;
    }

    .top-nav button:hover {
        background-color: #0056b3;
    }
</style>

<nav class="top-nav">
    <div>
        <a href="{{ route('matches.index') }}" class="{{ request()->routeIs('matches.index') ? 'active' : '' }}">TÜMÜ</a>
        <a href="{{ route('matches.live') }}" class="{{ request()->routeIs('matches.live') ? 'active' : '' }}">CANLI</a>
        <a href="{{ route('matches.finished') }}" class="{{ request()->routeIs('matches.finished') ? 'active' : '' }}">BİTMİŞ</a>
        <a href="{{ route('matches.odds') }}" class="{{ request()->routeIs('matches.odds') ? 'active' : '' }}">ORANLAR</a>
        <a href="{{ route('matches.scheduled') }}" class="{{ request()->routeIs('matches.scheduled') ? 'active' : '' }}">PROGRAMLAR</a>
    </div>
    <div>
        <form method="GET" action="{{ url('/') }}">
            <input type="date" name="date" value="{{ $selectedDate }}">
            <button type="submit">Tarihi Seç</button>
        </form>
    </div>
</nav>
