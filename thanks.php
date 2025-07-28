<?php
session_start();
if (!isset($_SESSION['success_message']) || !isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: selection_page.php");
    exit();
}

$message = $_SESSION['success_message'];
$username = $_SESSION['user_name'];
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Confirmation | Property Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #c0392b;
            --primary-dark: #a53125;
            --secondary: #e74c3c;
            --accent: #d63031;
            --success: #27ae60;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #f5f6fa, #e8e8e8, #f5f6fa, #e8e8e8);
            background-size: 400% 400%;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark);
            overflow-x: hidden;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        .confirmation-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 3.5rem;
            max-width: 640px;
            width: 90%;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            z-index: 10;
        }
        
        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .confirmation-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary));
        }
        
        h1 {
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            font-size: 2.4rem;
            font-weight: 700;
        }
        
        .user-greeting {
            font-size: 1.4rem;
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .success-message {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            color: var(--dark);
            line-height: 1.6;
            padding: 1.2rem;
            background: rgba(192, 57, 43, 0.08);
            border-radius: 12px;
            border-left: 4px solid var(--success);
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 1rem 2.2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(192, 57, 43, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--gray);
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.08);
        }
        
        .success-animation {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            position: relative;
        }
        
        .success-animation-circle {
            width: 100%;
            height: 100%;
            background: rgba(192, 57, 43, 0.1);
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            animation: circlePulse 2s cubic-bezier(0.22, 1, 0.36, 1) infinite;
        }
        
        .success-animation-icon {
            position: relative;
            z-index: 1;
            width: 60px;
            height: 60px;
            margin: 30px auto;
            background-color: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: scale(0);
            animation: iconScale 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards;
        }
        
        .success-animation-icon::after {
            content: '';
            width: 20px;
            height: 10px;
            border-left: 3px solid white;
            border-bottom: 3px solid white;
            transform: rotate(-45deg) translate(1px, -1px);
        }
        
        @keyframes circlePulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
        }
        
        @keyframes iconScale {
            0% {
                transform: scale(0);
            }
            80% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            background: rgba(192, 57, 43, 0.15);
            border-radius: 50%;
            animation: float linear infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Enhanced Celebration Effects */
        .celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }
        
        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 10px;
            opacity: 0;
            animation: confetti-fall 5s linear forwards;
        }
        
        .emoji-celebration {
            position: absolute;
            font-size: 2rem;
            opacity: 0;
            animation: emoji-float 4s ease-out forwards;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        @keyframes emoji-float {
            0% {
                transform: translateY(0) scale(0.5);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) scale(1.2);
                opacity: 0;
            }
        }
        
        /* Fireworks Effect */
        .firework {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            box-shadow: 0 0 10px 2px;
            animation: firework-explode 1s ease-out forwards;
        }
        
        @keyframes firework-explode {
            0% {
                transform: translate(var(--x), var(--y)) scale(0);
                opacity: 1;
            }
            100% {
                transform: translate(var(--x), var(--y)) scale(1);
                opacity: 0;
                box-shadow: 0 0 0 40px transparent;
            }
        }
        
        @media (max-width: 640px) {
            .confirmation-container {
                padding: 2.5rem 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .user-greeting {
                font-size: 1.2rem;
            }
            
            .button-group {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn {
                width: 100%;
                padding: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-particles" id="particles"></div>
    <div class="celebration" id="celebration"></div>
    
    <div class="confirmation-container">
        <div class="success-animation">
            <div class="success-animation-circle"></div>
            <div class="success-animation-icon"></div>
        </div>
        
        <h1>Submission Successful!</h1>
        <div class="user-greeting">Thank you, <?php echo htmlspecialchars($username); ?>!</div>
        <p class="success-message">✅ Property submitted successfully! Admin will verify it shortly and you can check your activity page for more information </p>
        
        <div class="button-group">
            <a href="selection_page.php" class="btn btn-primary">Back to Selection Page</a>
            <a href="logout.php" class="btn btn-secondary">Logout & Back to Home</a>
        </div>
    </div>

    <script>
        // Create floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            const colors = [
                'rgba(192, 57, 43, 0.15)',
                'rgba(231, 76, 60, 0.15)',
                'rgba(214, 48, 49, 0.15)'
            ];
            
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 20 + 10;
                const posX = Math.random() * 100;
                const duration = Math.random() * 15 + 10;
                const delay = Math.random() * 5;
                const color = colors[Math.floor(Math.random() * colors.length)];
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.background = color;
                
                container.appendChild(particle);
            }
        }
        
        // Create celebration effects
        function createCelebration() {
            const container = document.getElementById('celebration');
            const emojis = ['🎉', '✨', '🏆', '👍', '🎊', '👏', '💯', '🔥'];
            const colors = ['#e74c3c', '#f1c40f', '#2ecc71', '#3498db', '#9b59b6', '#1abc9c', '#e67e22', '#c0392b'];
            
            // Create confetti
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti-piece';
                confetti.style.left = `${Math.random() * 100}%`;
                confetti.style.top = `-10px`;
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = `${Math.random() * 5}s`;
                confetti.style.animationDuration = `${3 + Math.random() * 3}s`;
                container.appendChild(confetti);
            }
            
            // Create emoji celebration
            for (let i = 0; i < 20; i++) {
                const emoji = document.createElement('div');
                emoji.className = 'emoji-celebration';
                emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
                emoji.style.left = `${Math.random() * 100}%`;
                emoji.style.top = `${80 + Math.random() * 20}%`;
                emoji.style.animationDelay = `${Math.random() * 2}s`;
                container.appendChild(emoji);
            }
            
            // Create fireworks
            for (let i = 0; i < 8; i++) {
                setTimeout(() => {
                    createFirework(
                        Math.random() * window.innerWidth,
                        Math.random() * window.innerHeight / 2
                    );
                }, i * 300);
            }
        }
        
        function createFirework(x, y) {
            const container = document.getElementById('celebration');
            const colors = ['#e74c3c', '#f1c40f', '#2ecc71', '#3498db', '#9b59b6'];
            
            for (let i = 0; i < 30; i++) {
                const angle = (Math.PI * 2) * (i / 30);
                const distance = 5 + Math.random() * 50;
                const firework = document.createElement('div');
                firework.className = 'firework';
                firework.style.left = `${x}px`;
                firework.style.top = `${y}px`;
                firework.style.setProperty('--x', `${Math.cos(angle) * distance}px`);
                firework.style.setProperty('--y', `${Math.sin(angle) * distance}px`);
                firework.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                firework.style.boxShadow = `0 0 10px 2px ${colors[Math.floor(Math.random() * colors.length)]}`;
                container.appendChild(firework);
                
                // Remove after animation
                setTimeout(() => {
                    firework.remove();
                }, 1000);
            }
        }
        
        // Initialize on load
        window.addEventListener('load', () => {
            createParticles();
            setTimeout(createCelebration, 800);
            
            // Add pulse animation to buttons after delay
            setTimeout(() => {
                const buttons = document.querySelectorAll('.btn');
                buttons.forEach(btn => {
                    btn.style.animation = 'pulse 2s infinite';
                });
                
                const pulseStyle = document.createElement('style');
                pulseStyle.innerHTML = `
                    @keyframes pulse {
                        0% { transform: translateY(0); }
                        50% { transform: translateY(-3px); }
                        100% { transform: translateY(0); }
                    }
                `;
                document.head.appendChild(pulseStyle);
            }, 1500);
        });
    </script>
</body>
</html>