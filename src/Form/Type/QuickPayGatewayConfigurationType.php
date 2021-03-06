<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuickPayGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apikey', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.apikey',
                'constraints' => [
                    new NotBlank([
                        'message' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.apikey.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('privatekey', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.privatekey',
                'constraints' => [
                    new NotBlank([
                        'message' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.privatekey.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('merchant', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.merchant',
                'constraints' => [
                    new NotBlank([
                        'message' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.merchant.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('agreement', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.agreement',
                'constraints' => [
                    new NotBlank([
                        'message' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.agreement.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('order_prefix', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.order_prefix',
                'required' => false,
                'constraints' => [
                    new Length([
                        'maxMessage' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.order_prefix.max_length',
                        'max' => 11,
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('payment_methods', TextType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.payment_methods',
                'help' => 'https://learn.quickpay.net/tech-talk/appendixes/payment-methods/#payment-methods',
            ])
            ->add('auto_capture', ChoiceType::class, [
                'label' => 'setono_sylius_quickpay.form.gateway_configuration.quickpay.auto_capture',
                'choices' => [
                    'setono_sylius_quickpay.form.gateway_configuration.quickpay.auto_capture_option.no' => 0,
                    'setono_sylius_quickpay.form.gateway_configuration.quickpay.auto_capture_option.yes' => 1,
                ],
                'help' => 'https://learn.quickpay.net/tech-talk/guides/payments/#introduction-to-payments',
            ])
            ->add('use_authorize', HiddenType::class, [
                'data' => true,
            ])
        ;
    }
}
