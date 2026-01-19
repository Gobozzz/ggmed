<?php

declare(strict_types=1);

namespace App\FakeGenerators;

final class EditorGenerator
{

    private function __construct()
    {
    }

    const VERSION = '2.29.1';

    /**
     * Доступные типы блоков с их вероятностями
     */
    private array $blockTypes = [
        'paragraph' => 40,
        'header' => 20,
        'list' => 15,
        'image' => 10,
        'quote' => 10,
        'table' => 3,
        'delimiter' => 1,
    ];

    public static function make(int $blocksCount = 3): array
    {
        $object = new self();

        $blocks = [];

        for ($i = 0; $i < $blocksCount; $i++) {
            $blocks[] = $object->generateBlock();
        }

        return [
            'time' => now()->timestamp * 1000,
            'blocks' => $blocks,
            'version' => self::VERSION,
        ];
    }

    private function generateBlock(): array
    {
        $type = $this->getRandomBlockType();

        return [
            'id' => fake()->uuid(),
            'type' => $type,
            'data' => $this->generateBlockData($type),
        ];
    }

    /**
     * Получение случайного типа блока с учетом вероятностей
     */
    private function getRandomBlockType(): string
    {
        $total = array_sum($this->blockTypes);
        $random = rand(1, $total);
        $current = 0;

        foreach ($this->blockTypes as $type => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $type;
            }
        }

        return 'paragraph';
    }

    /**
     * Генерация данных для конкретного типа блока
     */
    private function generateBlockData(string $type): array
    {
        return match ($type) {
            'paragraph' => [
                'text' => fake()->paragraphs(rand(1, 3), true),
            ],
            'header' => [
                'text' => fake()->sentence(),
                'level' => rand(1, 3),
            ],
            'list' => [
                'style' => fake()->randomElement(['ordered', 'unordered']),
                'items' => array_map(
                    fn() => fake()->sentence(),
                    array_fill(0, rand(2, 6), null)
                ),
            ],
            'image' => [
                'file' => [
                    'url' => $this->getRandomImageUrl(),
                ],
                'caption' => rand(0, 1) ? fake()->sentence() : '',
                'withBorder' => rand(0, 4) === 0,
                'withBackground' => rand(0, 9) === 0,
                'stretched' => rand(0, 9) === 0,
            ],
            'quote' => [
                'text' => fake()->paragraph(),
                'caption' => fake()->name(),
                'alignment' => fake()->randomElement(['left', 'center']),
            ],
            'table' => [
                'withHeadings' => rand(0, 9) < 7,
                'content' => $this->generateTableContent(),
            ],
            'delimiter' => [],
            'code' => [
                'code' => $this->generateCodeSnippet(),
                'language' => fake()->randomElement(['php', 'javascript', 'html', 'css']),
            ],
            default => [
                'text' => fake()->paragraph(),
            ],
        };
    }

    /**
     * Генерация фрагмента кода
     */
    private function generateCodeSnippet(): string
    {
        $snippets = [
            'php' => "<?php\n\nnamespace App\\Services;\n\nclass ExampleService\n{\n    public function execute()\n    {\n        return 'Hello World';\n    }\n}",
            'javascript' => "const example = () => {\n    console.log('Hello World');\n    return {\n        success: true,\n        data: []\n    };\n};",
            'html' => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Example</title>\n</head>\n<body>\n    <h1>Hello World</h1>\n</body>\n</html>",
            'css' => ".container {\n    max-width: 1200px;\n    margin: 0 auto;\n    padding: 20px;\n}\n\n.button {\n    background-color: #007bff;\n    color: white;\n    padding: 10px 20px;\n    border-radius: 5px;\n}",
        ];

        return fake()->randomElement($snippets);
    }

    /**
     * Генерация URL случайного изображения
     */
    private function getRandomImageUrl(): string
    {
        return 'https://picsum.photos/800/600?random=' . rand(1, 100);
    }

    /**
     * Генерация содержимого таблицы
     */
    private function generateTableContent(): array
    {
        $rows = rand(2, 5);
        $cols = rand(2, 4);

        $content = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $cols; $j++) {
                if ($i === 0 && rand(0, 9) < 7) {
                    $row[] = fake()->words(rand(1, 3), true);
                } else {
                    $row[] = fake()->sentence(rand(2, 5));
                }
            }
            $content[] = $row;
        }

        return $content;
    }

}
