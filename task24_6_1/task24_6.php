<?php
 
//namespace RefactoringGuru\Iterator\RealWorld;
 
/**
 * Итератор CSV-файлов.
 *
 * @author Josh Lockhart
 */
class MyClass implements Iterator
{
    const ROW_SIZE = 4096;
 
    protected $filePointer = null;
 
    protected $currentElement = null;
 
    // Счётчик строк.
    protected $rowCounter = null;
 
 
    // Конструктор пытается открыть файл. Он выдаёт исключение при ошибке.
    public function __construct($file)
    {
        try {
            $this->filePointer = fopen($file, 'rb');
        } catch (\Exception $e) {
            throw new \Exception('The file "' . $file . '" cannot be read.');
        }
    }
 
    // Этот метод сбрасывает указатель файла.
    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }
 
    //Этот метод возвращает текущую строку  
    public function current(): string
    {
        $this->currentElement = fgets($this->filePointer, self::ROW_SIZE);
        $this->rowCounter++;
 
        return $this->currentElement;
    }
 
    //Этот метод возвращает номер текущей строки.
    public function key(): int
    {
        return $this->rowCounter;
    }
 
    // Этот метод проверяет, достигнут ли конец файла. 
    public function next(): bool
    {
        if (is_resource($this->filePointer)) {
            return !feof($this->filePointer);
        }
 
        return false;
    }
 
    // Этот метод проверяет, является ли следующая строка допустимой.
    public function valid(): bool
    {
        if (!$this->next()) {
            if (is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }
 
            return false;
        }
 
        return true;
    }
}

$text = new MyClass(__DIR__ . '\file.html');

$filename = "editedFile.html";

foreach ($text as $key => $row) {
    $begin = stristr($row, 'meta'); 
    $end = stristr($begin, '>', true); 
   
    print_r ($row);
    if ($end == '') {
    file_put_contents($filename, $row, FILE_APPEND);
    }
}
