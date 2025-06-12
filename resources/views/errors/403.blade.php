<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        :root {
            --primary: #6b46c1;
            --secondary: #1a202c;
            --accent: #9f7aea;
            --error: #f56565;
            --text: #e2e8f0;
            --light-text: #a0aec0;
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
        
        .lock-container {
            width: 180px;
            height: 180px;
            margin: 0 auto 2rem;
            position: relative;
            perspective: 1000px;
        }
        
        .lock {
            width: 100px;
            height: 120px;
            background: linear-gradient(145deg, #2d3748, #4a5568);
            border-radius: 10px;
            margin: 0 auto;
            position: relative;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border: 3px solid var(--error);
            transform-style: preserve-3d;
            animation: shake 0.5s ease-in-out infinite alternate;
        }
        
        @keyframes shake {
            0% { transform: rotateZ(-1deg); }
            100% { transform: rotateZ(1deg); }
        }
        
        .lock-top {
            width: 60px;
            height: 40px;
            background: linear-gradient(145deg, #2d3748, #4a5568);
            border-radius: 30px 30px 0 0;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            border: 3px solid var(--error);
            border-bottom: none;
        }
        
        .lock-hole {
            width: 20px;
            height: 20px;
            background-color: var(--error);
            border-radius: 50%;
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 10px var(--error);
        }
        
        .lock-hole::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 30px;
            background-color: var(--error);
            border-radius: 5px;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 8px var(--error);
        }
        
        .no-access {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: var(--error);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            right: 0;
            top: 0;
            transform: translate(10px, -10px);
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: translate(10px, -10px) scale(1); }
            50% { transform: translate(10px, -10px) scale(1.1); }
        }
        
        h1 {
            font-size: 5rem;
            color: var(--error);
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(245, 101, 101, 0.3);
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
            margin: 0 0.5rem;
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
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--accent);
            color: var(--accent);
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: rgba(159, 122, 234, 0.1);
        }
        
        .btn-group {
            margin-top: 1.5rem;
        }
        
        .laser-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            opacity: 0.1;
        }
        
        .laser {
            position: absolute;
            background-color: var(--error);
            box-shadow: 0 0 8px var(--error);
        }
        
        .laser-h {
            width: 100%;
            height: 1px;
            animation: scan-h 6s linear infinite;
        }
        
        .laser-v {
            width: 1px;
            height: 100%;
            animation: scan-v 7s linear infinite;
        }
        
        @keyframes scan-h {
            0% { top: 0; }
            100% { top: 100%; }
        }
        
        @keyframes scan-v {
            0% { left: 0; }
            100% { left: 100%; }
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 3.5rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .lock-container {
                width: 150px;
                height: 150px;
            }
            
            .btn {
                display: block;
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="laser-grid">
        <div class="laser laser-h" style="top: 20%; animation-delay: 0s;"></div>
        <div class="laser laser-h" style="top: 40%; animation-delay: 1s;"></div>
        <div class="laser laser-h" style="top: 60%; animation-delay: 2s;"></div>
        <div class="laser laser-v" style="left: 25%; animation-delay: 0.5s;"></div>
        <div class="laser laser-v" style="left: 50%; animation-delay: 1.5s;"></div>
        <div class="laser laser-v" style="left: 75%; animation-delay: 2.5s;"></div>
    </div>
    
    <div class="error-container">
        <div class="lock-container">
            <div class="no-access">!</div>
            <div class="lock-top"></div>
            <div class="lock">
                <div class="lock-hole"></div>
            </div>
        </div>
        
        <h1>403</h1>
        <h2>Access Denied</h2>
        <p>You don't have permission to access this page.</p>
        
        <div class="btn-group">
            <a href="/" class="btn">Go to Homepage</a>
        </div>
    </div>
</body>
</html>