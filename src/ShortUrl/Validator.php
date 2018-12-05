<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 24.11.18
 * Time: 14:27
 */

namespace App\ShortUrl;


use App\Entity\ShortUrl;
use App\ShortUrl\Exception\ShortUrlIsNotValidException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Validator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ShortUrl $shortUrl
     * @return bool
     * @throws ShortUrlIsNotValidException
     */
    public function validate(ShortUrl $shortUrl): bool
    {
        $errors = $this->validator->validate($shortUrl);

        if (count($errors) > 0) {
            $msg = '';
            foreach($errors as $key => $error) {

                $msg.= $error->getMessage();

                if($key > 0) {
                    $msg.= ',';
                }
            }

            throw new ShortUrlIsNotValidException($msg);
        }

        return true;
    }

}