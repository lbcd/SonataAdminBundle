<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DateTimeType.
 *
 * @author  Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class DateTimeType extends AbstractType
{
    const TYPE_GREATER_EQUAL = 1;

    const TYPE_GREATER_THAN = 2;

    const TYPE_EQUAL = 3;

    const TYPE_LESS_EQUAL = 4;

    const TYPE_LESS_THAN = 5;

    const TYPE_NULL = 6;

    const TYPE_NOT_NULL = 7;

    /**
     * @deprecated since 3.5, to be removed with 4.0
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * NEXT_MAJOR: Remove when dropping Symfony <2.8 support.
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_filter_datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_EQUAL => 'label_date_type_equal',
            self::TYPE_GREATER_EQUAL => 'label_date_type_greater_equal',
            self::TYPE_GREATER_THAN => 'label_date_type_greater_than',
            self::TYPE_LESS_EQUAL => 'label_date_type_less_equal',
            self::TYPE_LESS_THAN => 'label_date_type_less_than',
            self::TYPE_NULL => 'label_date_type_null',
            self::TYPE_NOT_NULL => 'label_date_type_not_null',
        );

        if (!method_exists('Symfony\Component\Form\FormTypeInterface', 'setDefaultOptions')) {
            $choices = array_flip($choices);
        }

        // NEXT_MAJOR: Remove this hack, when dropping support for symfony <2.7
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            foreach ($choices as $key => $value) {
                $choices[$key] = $this->translator->trans($value, array(), 'SonataAdminBundle');
            }
        }

        $choiceOptions = array(
            'choices' => $choices,
            'required' => false,
        );

        // NEXT_MAJOR: Remove this hack, when dropping support for symfony <2.7
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $choiceOptions['choice_translation_domain'] = 'SonataAdminBundle';
        }

        $builder
            ->add('type', 'choice', $choiceOptions)
            ->add('value', $options['field_type'], array_merge(array('required' => false), $options['field_options']))
        ;
    }

    /**
     * NEXT_MAJOR: Remove method, when bumping requirements to SF 2.7+.
     *
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'field_type' => 'datetime',
            'field_options' => array('date_format' => 'yyyy-MM-dd'),
        ));
    }
}
