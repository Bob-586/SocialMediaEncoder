<?php

/**
  The MIT License (MIT)

  @copyright (c) 2013 Kazuyuki Hayashi

  Permission is hereby granted, free of charge, to any person obtaining a copy of
  this software and associated documentation files (the "Software"), to deal in
  the Software without restriction, including without limitation the rights to
  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
  the Software, and to permit persons to whom the Software is furnished to do so,
  subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

  @link https://packagist.org/packages/kzykhys/steganography
 * 
 */
class steganography {

    const BITS_PER_PIXEL = 3;
    const LENGTH_BITS = 48;

    public function encode($file, $message, int $quality = 0) {
        $image = new Image($file);
        if ($quality > 0) {
            $image->setQuality($quality);
        }
        $message = $this->base64_encoder(gzcompress($message, 9));
        $pixels = ceil(strlen($message) / self::BITS_PER_PIXEL + (self::LENGTH_BITS / self::BITS_PER_PIXEL));

        if ($pixels > $image->getPixels()) {
            throw new \LogicException('Number of pixels is fewer than ' . $pixels);
        }

        $image->setBinaryString(new BinaryIterator($message));

        return $image;
    }

    /**
     * @param string $file
     * @param array  $options
     *
     * @return mixed
     */
    public function decode($file) {
        $image = new Image($file);
        return gzuncompress($this->base64_decoder($image->getBinaryString()));
    }

    public function base64_encoder($data) {
        $compressed = base64_encode($data);
        $bin = '';
        $length = strlen($compressed);

        for ($i = 0; $i < $length; $i++) {
            $bin .= sprintf('%08b', ord($compressed[$i]));
        }

        return $bin;
    }

    /**
     * {@inheritdoc}
     */
    public function base64_decoder($data) {
        $chars = str_split($data, 8);
        $compressed = '';

        foreach ($chars as $char) {
            $compressed .= chr(bindec($char));
        }

        return base64_decode($compressed);
    }

}

/**
 * @author Kazuyuki Hayashi
 */
class BinaryIterator implements \Iterator {

    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var int
     */
    private $length = 0;

    /**
     * @var int
     */
    private $count = steganography::BITS_PER_PIXEL;

    /**
     * @param     $string
     * @param int $count
     */
    public function __construct($string, $count = steganography::BITS_PER_PIXEL) {
        $this->count = $count;
        $this->string = sprintf('%048b', strlen($string)) . $string;
        $this->length = strlen($this->string);
    }

    /**
     * Return the current element
     *
     * @return mixed Can return any type.
     */
    public function current() {
        $part = substr($this->string, ($this->index * $this->count), $this->count);
        $chars = array_pad(str_split($part), $this->count, 0);

        return [
            'r' => $chars[0],
            'g' => $chars[1],
            'b' => $chars[2],
        ];
    }

    /**
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next() {
        $this->index++;
    }

    /**
     * Return the key of the current element
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid() {
        return $this->index * $this->count < $this->length;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        $this->index = 0;
    }

}

/**
 * @author Kazuyuki Hayashi
 */
class RectIterator implements \Iterator {

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var int
     */
    private $x = 0;

    /**
     * @var int
     */
    private $y = 0;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width = 0, $height = 0) {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Return the current element
     *
     * @return mixed Can return any type.
     */
    public function current() {
        return [$this->x, $this->y];
    }

    /**
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next() {
        if ($this->x + 1 < $this->width) {
            $this->x++;
        } else {
            $this->x = 0;
            $this->y++;
        }

        $this->index++;
    }

    /**
     * Return the key of the current element
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid() {
        return $this->x < $this->width && $this->y < $this->height;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        $this->index = 0;
        $this->x = 0;
        $this->y = 0;
    }

}

/**
 * @author Kazuyuki Hayashi
 */
class Image {

    /**
     * @var string
     */
    private $path;

    /**
     * @var resource
     */
    private $image;

    /**
     * @var int
     */
    private $width = 0;
    private $height = 0;
    private $pixels = 0;
    private $quality = 0;

    public function setQuality(int $level): void {
        $this->quality = $level;
    }

    /**
     * @param string $path
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($path) {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File Not Found: ' . $path);
        }

        $this->path = $path;

        $this->initialize();
    }

    /**
     * @param BinaryIterator $binary
     *
     * @return $this
     */
    public function setBinaryString(BinaryIterator $binary) {
        $iterator = new \MultipleIterator(\MultipleIterator::MIT_NEED_ALL | \MultipleIterator::MIT_KEYS_ASSOC);
        $iterator->attachIterator(new RectIterator($this->width, $this->height), 'rect');
        $iterator->attachIterator($binary, 'bin');

        foreach ($iterator as $current) {
            $this->setPixel($current['rect'][0], $current['rect'][1], $current['bin']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBinaryString() {
        $iterator = new RectIterator($this->width, $this->height);
        $length = '';
        $data = '';
        $offset = steganography::LENGTH_BITS / steganography::BITS_PER_PIXEL;

        foreach (new \LimitIterator($iterator, 0, $offset) as $value) {
            $length .= $this->getPixel($value[0], $value[1]);
        }

        $bits = (int) bindec($length);
        $length = (int) ceil($bits / steganography::BITS_PER_PIXEL);

        foreach (new \LimitIterator($iterator, $offset, $length) as $value) {
            $data .= $this->getPixel($value[0], $value[1]);
        }

        $data = substr($data, 0, $bits);

        return $data;
    }

    /**
     * @param $x
     * @param $y
     * @param $values
     *
     * @return $this
     */
    public function setPixel($x, $y, $values) {
        $rgb = $this->getRGB($x, $y);

        foreach ($rgb as $name => $value) {
            $rgb[$name] = bindec(substr(decbin($value), 0, -1) . $values[$name]);
        }

        $color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);
        imagesetpixel($this->image, $x, $y, $color);
        imagecolordeallocate($this->image, $color);

        return $this;
    }

    /**
     * @param $x
     * @param $y
     *
     * @return string
     */
    public function getPixel($x, $y) {
        $result = '';
        $rgb = $this->getRGB($x, $y);

        foreach ($rgb as $value) {
            $result .= substr(decbin($value), -1, 1);
        }

        return $result;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function write($path) {
        return imagepng($this->image, $path, $this->quality);
    }

    /**
     * @return bool
     */
    public function render() {
        return imagepng($this->image, null, $this->quality);
    }

    /**
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getPixels() {
        return $this->pixels;
    }

    /**
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    public function __destruct() {
        if ($this->image) {
            imagedestroy($this->image);
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @return resource
     */
    protected function initialize() {
        $info = getimagesize($this->path);
        $this->width = $info[0];
        $this->height = $info[1];
        $this->pixels = $this->width * $this->height;
        $type = $info[2];

        switch ($type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($this->path);
                break;
            case IMAGETYPE_GIF;
                $this->image = imagecreatefromgif($this->path);
                break;
            case IMAGETYPE_PNG;
                $this->image = imagecreatefrompng($this->path);
                break;
            default:
                throw new \RuntimeException('Unsupport image type ' . $type);
        }

        imagealphablending($this->image, false);
    }

    /**
     * @param $x
     * @param $y
     *
     * @return array
     */
    protected function getRGB($x, $y) {
        $rgb = imagecolorat($this->image, $x, $y);

        return [
            'r' => ($rgb >> 16) & 0xFF,
            'g' => ($rgb >> 8) & 0xFF,
            'b' => $rgb & 0xFF
        ];
    }

}
