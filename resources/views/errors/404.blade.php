<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <style>
        :root {
            --primary: #6b46c1;
            --secondary: #1a202c;
            --accent: #9f7aea;
            --text: #e2e8f0;
            --light-text: #a0aec0;
            --error: #f56565;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--secondary);
            color: var(--text);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            overflow: hidden;
        }
        
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .floating-books {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
        }
        
        .book {
            position: absolute;
            background: linear-gradient(145deg, #2d3748, #4a5568);
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-left: 4px solid var(--accent);
            transform-origin: center bottom;
        }
        
        .book-1 {
            width: 100px;
            height: 140px;
            left: 0;
            top: 0;
            animation: float 4s ease-in-out infinite;
        }
        
        .book-2 {
            width: 120px;
            height: 160px;
            left: 40px;
            top: 20px;
            animation: float 4s ease-in-out infinite 0.5s;
            border-left-color: var(--primary);
        }
        
        .book-3 {
            width: 90px;
            height: 130px;
            left: 80px;
            top: 10px;
            animation: float 4s ease-in-out infinite 1s;
            border-left-color: var(--error);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        
        .search-icon {
            position: absolute;
            font-size: 2.5rem;
            color: var(--accent);
            right: 10px;
            top: 60px;
            z-index: 3;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        h1 {
            font-size: 5rem;
            color: var(--accent);
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(159, 122, 234, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { text-shadow: 0 0 5px rgba(159, 122, 234, 0.3); }
            to { text-shadow: 0 0 15px rgba(159, 122, 234, 0.6); }
        }
        
        h2 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        p {
            color: var(--light-text);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(107, 70, 193, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(107, 70, 193, 0.4);
        }
        
        .btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
            transition: all 0.3s;
        }
        
        .btn:hover::after {
            left: 100%;
        }
        
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            background-color: var(--accent);
            border-radius: 50%;
            opacity: 0;
            animation: particle-float linear infinite;
        }
        
        @keyframes particle-float {
            0% { 
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { 
                transform: translateY(-100vh) translateX(20px);
                opacity: 0;
            }
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 3.5rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .floating-books {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="error-container">
        <div class="floating-books">
            <div class="book book-1"></div>
            <div class="book book-2"></div>
            <div class="book book-3"></div>
            <div class="search-icon">🔍</div>
        </div>
        
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or may have been moved.</p>
        
        <a href="/" class="btn">Return to Homepage</a>
    </div>

    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Random size between 1px and 3px
            const size = Math.random() * 2 + 1;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.bottom = `-10px`;
            
            // Random animation duration between 10s and 20s
            const duration = `${Math.random() * 10 + 10}s`;
            particle.style.animationDuration = duration;
            
            // Random delay
            particle.style.animationDelay = `${Math.random() * 10}s`;
            
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>