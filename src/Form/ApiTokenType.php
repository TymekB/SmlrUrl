<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 22.11.18
 * Time: 20:38
 */

namespace App\Form;


use App\Entity\ApiToken;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiTokenType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => ApiToken::class,
        ));
    }


}