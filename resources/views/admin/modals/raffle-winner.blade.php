@if($winner === null)
    <div class="text-center">Не нашлось победителя</div>
@else
    <style>
        /* Общие стили */
        .winner-card {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .winner-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .winner-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .info-item {
            background-color: #f5f5f5;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 16px;
            transition: opacity 0.5s ease;
            opacity: 0;
        }

        .phone-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .call-btn {
            padding: 6px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 8px;
            text-decoration: none;
        }

        .call-btn:hover {
            background-color: #0056b3;
        }

        /* Современный крутой спиннер */
        .loader-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .loading-text {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .loader {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(270deg, #f3f3f3, #007BFF, #f3f3f3);
            background-size: 600% 600%;
            animation: spinGradient 1.5s linear infinite, pulse 2s infinite;
        }

        @keyframes spinGradient {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        /* Анимации появления */
        .show {
            opacity: 1;
        }

        /* Центрирование блока */
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
    </style>

    {{-- Блок с "Выбираю..." и спиннером --}}
    <div id="loadingContainer" class="loader-container">
        <div class="loading-text">Выбираю...</div>
        <div class="loader"></div>
    </div>

    {{-- Основной контент --}}
    <div class="winner-card" id="winnerCard" style="display:none;">
        <div class="winner-title">Победитель розыгрыша</div>
        <div class="winner-info" id="winnerInfo">
            <div class="info-item" id="name"><strong>Имя:</strong> {{ $winner->name }}</div>
            <div class="info-item" id="email"><strong>Email:</strong> {{ $winner->email }}</div>
            <div class="info-item" id="phone">
                <strong>Телефон:</strong>
                @if($winner->phone)
                    <div>
                        <span>{{ $winner->phone }}</span>
                        <div class="phone-container">
                            <a target="_blank" href="tel:{{ $winner->phone }}" class="call-btn">Позвонить</a>
                            <a target="_blank" href="https://t.me/{{ $winner->phone }}" class="call-btn">TG</a>
                            <a target="_blank" href="https://wa.me/{{ $winner->phone }}" class="call-btn">WH-APP</a>
                        </div>
                    </div>
                @else
                    Не указан
                @endif
            </div>
        </div>
    </div>

    <script>
        // Вызов функции сразу, с задержкой 4000 мс
        function loadWinner() {
            const loaderContainer = document.getElementById('loadingContainer');
            const card = document.getElementById('winnerCard');
            const info = document.getElementById('winnerInfo');

            // Запускаем анимацию через 4 секунды
            setTimeout(() => {
                // скрываем "выбираю..."
                loaderContainer.style.display = 'none';

                // показываем карточку
                card.style.display = 'block';
                // плавно делаем видимой
                card.classList.add('show');

                // задержка для плавного появления содержимого
                setTimeout(() => {
                    info.classList.add('show');

                    // плавное появление каждого элемента
                    const items = document.querySelectorAll('#winnerInfo .info-item');
                    items.forEach((el, index) => {
                        setTimeout(() => {
                            el.classList.add('show');
                        }, (index + 1) * 300);
                    });
                }, 1000);
            }, 4000);
        }

        // Вызов функции сразу
        loadWinner();
    </script>
@endif
