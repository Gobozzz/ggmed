<?php

declare(strict_types=1);

namespace App\MoonShine\Converters;

final readonly class MarkdownToEditorJsConverter
{
    public function convert(string $markdownText): array
    {
        $lines = explode("\n", $markdownText);
        $blocks = [];
        $currentListItems = [];
        $listStyle = null;
        $inCodeBlock = false;
        $codeContent = '';
        $codeLanguage = '';
        $i = 0;
        $totalLines = count($lines);

        while ($i < $totalLines) {
            $line = $lines[$i];

            // Обработка блоков кода
            if (str_starts_with($line, '```')) {
                if (!$inCodeBlock) {
                    // Начало блока кода
                    $inCodeBlock = true;
                    $codeContent = '';
                    $codeLanguage = trim(substr($line, 3));
                } else {
                    // Конец блока кода
                    $inCodeBlock = false;
                    $blocks[] = [
                        'type' => 'code',
                        'data' => [
                            'code' => trim($codeContent),
                            'language' => $codeLanguage
                        ]
                    ];
                }
                $i++;
                continue;
            }

            if ($inCodeBlock) {
                $codeContent .= $line . "\n";
                $i++;
                continue;
            }

            // Заголовки
            if (preg_match('/^(#{1,6})\s(.+)$/', $line, $matches)) {
                $this->finalizeList($blocks, $currentListItems, $listStyle);

                $level = strlen($matches[1]);
                $text = $matches[2];

                $blocks[] = [
                    'type' => 'header',
                    'data' => [
                        'text' => $this->processInlineMarkdown($text),
                        'level' => $level
                    ]
                ];
                $i++;
                continue;
            }

            // Маркированные списки
            if (preg_match('/^(\s*)[-*+]\s(.+)$/', $line, $matches)) {
                $indent = strlen($matches[1] ?? '');
                $text = $matches[2];

                // Если это начало нового списка
                if (empty($currentListItems) || $listStyle !== 'unordered') {
                    $this->finalizeList($blocks, $currentListItems, $listStyle);
                    $listStyle = 'unordered';
                }

                $this->addListItem($currentListItems, $indent, $text);
                $i++;
                continue;
            }

            // Нумерованные списки
            if (preg_match('/^(\s*)\d+\.\s(.+)$/', $line, $matches)) {
                $indent = strlen($matches[1] ?? '');
                $text = $matches[2];

                // Если это начало нового списка
                if (empty($currentListItems) || $listStyle !== 'ordered') {
                    $this->finalizeList($blocks, $currentListItems, $listStyle);
                    $listStyle = 'ordered';
                }

                $this->addListItem($currentListItems, $indent, $text);
                $i++;
                continue;
            }

            // Цитаты
            if (str_starts_with($line, '> ')) {
                $this->finalizeList($blocks, $currentListItems, $listStyle);

                $quoteText = substr($line, 2);
                // Проверяем следующие строки, если они тоже цитаты
                while ($i + 1 < $totalLines && str_starts_with($lines[$i + 1], '> ')) {
                    $i++;
                    $quoteText .= "\n" . substr($lines[$i], 2);
                }

                $blocks[] = [
                    'type' => 'quote',
                    'data' => [
                        'text' => $this->processInlineMarkdown($quoteText),
                        'caption' => '',
                        'alignment' => 'left'
                    ]
                ];
                $i++;
                continue;
            }

            // Разделитель
            if (preg_match('/^[-*_]{3,}$/', $line)) {
                $this->finalizeList($blocks, $currentListItems, $listStyle);

                $blocks[] = [
                    'type' => 'delimiter',
                    'data' => new \stdClass()
                ];
                $i++;
                continue;
            }

            // Изображения
            if (preg_match('/^!\[([^\]]*)\]\(([^)]+)\)$/', $line, $matches)) {
                $this->finalizeList($blocks, $currentListItems, $listStyle);

                $blocks[] = [
                    'type' => 'image',
                    'data' => [
                        'url' => $matches[2],
                        'caption' => $matches[1],
                        'withBorder' => false,
                        'withBackground' => false,
                        'stretched' => false
                    ]
                ];
                $i++;
                continue;
            }

            // Параграфы (все остальное)
            if (trim($line) !== '') {
                $this->finalizeList($blocks, $currentListItems, $listStyle);

                // Обработка inline-разметки
                $processedText = $this->processInlineMarkdown($line);

                // Объединяем с последующими строками параграфа
                $paragraphText = $processedText;
                while ($i + 1 < $totalLines &&
                    trim($lines[$i + 1]) !== '' &&
                    !preg_match('/^(#{1,6}|[-*+]\s|>\s|\d+\.\s|```|!\[)/', $lines[$i + 1]) &&
                    !preg_match('/^[-*_]{3,}$/', $lines[$i + 1])) {
                    $i++;
                    $paragraphText .= ' ' . $this->processInlineMarkdown($lines[$i]);
                }

                $blocks[] = [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => trim($paragraphText)
                    ]
                ];
                $i++;
            } else {
                // Пустая строка - завершаем текущий список
                $this->finalizeList($blocks, $currentListItems, $listStyle);
                $i++;
            }
        }

        // Завершаем последний список, если он есть
        $this->finalizeList($blocks, $currentListItems, $listStyle);

        // Формируем финальный объект EditorJS
        return [
            'time' => time() * 1000, // время в миллисекундах
            'blocks' => $blocks,
            'version' => "2.26.5"
        ];
    }

    /**
     * Добавляет элемент списка с учетом вложенности
     *
     * @param array &$items
     * @param int $indent
     * @param string $text
     * @param int $level
     */
    private function addListItem(array &$items, int $indent, string $text, int $level = 0): void
    {
        $currentLevel = (int)($indent / 2); // 2 пробела = 1 уровень вложенности

        // Если это элемент текущего уровня
        if ($currentLevel === $level) {
            $items[] = [
                'content' => $this->processInlineMarkdown($text),
                'meta' => new \stdClass(),
                'items' => []
            ];
        }
        // Если элемент должен быть вложенным
        elseif ($currentLevel > $level && !empty($items)) {
            $lastIndex = count($items) - 1;
            $this->addListItem($items[$lastIndex]['items'], $indent, $text, $level + 1);
        }
    }

    /**
     * Завершает текущий список и добавляет его в блоки
     *
     * @param array &$blocks
     * @param array &$items
     * @param string|null $style
     */
    private function finalizeList(array &$blocks, array &$items, ?string &$style): void
    {
        if (!empty($items) && $style) {
            $blocks[] = [
                'type' => 'list',
                'data' => [
                    'style' => $style,
                    'items' => $items
                ]
            ];

            $items = [];
            $style = null;
        }
    }

    /**
     * Обработка inline-разметки Markdown
     *
     * @param string $text
     * @return string
     */
    private function processInlineMarkdown(string $text): string
    {
        // Жирный текст: **text** или __text__
        $text = preg_replace('/\*\*(.*?)\*\*|__(.*?)__/s', '<strong>$1$2</strong>', $text);

        // Курсив: *text* или _text_
        $text = preg_replace('/\*(.*?)\*|_(.*?)_/s', '<em>$1$2</em>', $text);

        // Зачеркнутый текст: ~~text~~
        $text = preg_replace('/~~(.*?)~~/s', '<s>$1</s>', $text);

        // Inline-код: `code`
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);

        // Ссылки: [text](url)
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text);

        return $text;
    }

    /**
     * Конвертирует и возвращает JSON строку
     *
     * @param string $markdownText
     * @return string
     */
    public function convertToJson(string $markdownText): string
    {
        return json_encode($this->convert($markdownText), JSON_UNESCAPED_UNICODE);
    }
}
