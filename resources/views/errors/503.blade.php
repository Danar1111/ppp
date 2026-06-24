<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Dalam Pemeliharaan - Sistem Manajemen PPP</title>
    <style>
        :root {
            --ppp-green: #0D8A4E;
            --ppp-green-light: #16A362;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Fallback font stack that looks modern across devices */
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
            /* Subtle dotted background pattern for a premium feel */
            background-image: radial-gradient(circle at 1px 1px, #e5e7eb 1px, transparent 0);
            background-size: 24px 24px;
        }

        .maintenance-container {
            background: var(--card-bg);
            max-width: 640px;
            width: 100%;
            padding: 3.5rem 4rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Top accent line */
        .maintenance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--ppp-green);
        }

        .brand-title {
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logo-icon {
            width: 24px;
            height: 24px;
            fill: var(--ppp-green);
        }

        .title-group {
            margin-bottom: 1.5rem;
        }

        .main-heading {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .main-heading span {
            color: var(--ppp-green);
        }

        .message {
            font-size: 1.125rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 3rem;
        }

        /* Status Indicator & Pulse Animation */
        .status-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: #ecfdf5; /* Light green background */
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ppp-green);
            border: 1px solid #d1fae5;
        }

        .pulse-dot {
            position: relative;
            width: 10px;
            height: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pulse-dot::before {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--ppp-green);
            border-radius: 50%;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--ppp-green);
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            100% {
                transform: scale(3.5);
                opacity: 0;
            }
        }

        /* Glowing Line */
        .glowing-line-container {
            width: 100%;
            height: 2px;
            background: #f3f4f6;
            margin-top: 3rem;
            position: relative;
            overflow: hidden;
            border-radius: 2px;
        }

        .glowing-line {
            position: absolute;
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, transparent, var(--ppp-green), transparent);
            animation: scan 2.5s ease-in-out infinite;
        }

        @keyframes scan {
            0% { left: -40%; }
            100% { left: 100%; }
        }

        @media (max-width: 640px) {
            .maintenance-container {
                padding: 2.5rem 1.5rem;
            }
            .main-heading {
                font-size: 1.75rem;
            }
            .message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="maintenance-container">
        
        <div class="brand-title">
            <svg class="logo-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <!-- A geometric structure/building icon to represent organization -->
                <path d="M12 2L3 7l9 5 9-5-9-5zM3 17l9 5 9-5V9l-9 5-9-5v8z"/>
            </svg>
            SISTEM MANAJEMEN PPP
        </div>
        
        <div class="title-group">
            <h1 class="main-heading">Sistem Dalam <span>Pemeliharaan</span></h1>
        </div>

        <p class="message">
            Mohon maaf, saat ini kami sedang melakukan pembaruan rutin dan optimalisasi server untuk meningkatkan kenyamanan Anda. Sistem akan segera beroperasi kembali dalam beberapa saat.
        </p>

        <div class="status-wrapper">
            <div class="status-indicator">
                <div class="pulse-dot"></div>
                <span>Server Sedang Dioptimalkan</span>
            </div>
        </div>

        <div class="glowing-line-container">
            <div class="glowing-line"></div>
        </div>

    </div>

</body>
</html>
