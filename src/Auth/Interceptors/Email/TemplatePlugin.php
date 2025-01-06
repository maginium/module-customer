<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interceptors\Email;

use Magento\Config\Model\Config\Source\Email\Template;
use Magento\Email\Model\Template\Config;
use Maginium\Framework\Support\Arr;

/**
 * Plugin to add custom email templates to the available email template options.
 *
 * This plugin modifies the email template options array by adding custom email templates
 * to the list of available options in Magento's email configuration.
 */
class TemplatePlugin
{
    /**
     * List of custom email template IDs that should be added to the options.
     */
    public const AVAILABLE_EMAIL_TEMPLATES = [
        'enhanced_email_templates_reset_password',
        'enhanced_email_templates_customer_new_account',
        'enhanced_email_templates_customer_change_email',
        'enhanced_email_templates_customer_password_remind',
        'enhanced_email_templates_customer_change_email_pass',
        'enhanced_email_templates_customer_new_account_confirmed',
        'enhanced_email_templates_customer_password_forgot_email',
        'enhanced_email_templates_customer_new_account_no_password',
        'enhanced_email_templates_customer_new_account_confirmation',

        // Rtl
        'enhanced_email_templates_reset_password_rtl',
        'enhanced_email_templates_customer_new_account_rtl',
        'enhanced_email_templates_customer_change_email_rtl',
        'enhanced_email_templates_customer_password_remind_rtl',
        'enhanced_email_templates_customer_change_email_pass_rtl',
        'enhanced_email_templates_customer_new_account_confirmed_rtl',
        'enhanced_email_templates_customer_password_forgot_email_rtl',
        'enhanced_email_templates_customer_new_account_no_password_rtl',
        'enhanced_email_templates_customer_new_account_confirmation_rtl',
    ];

    /**
     * @var Config The email template configuration service
     */
    protected Config $emailConfig;

    /**
     * TemplatePlugin constructor.
     *
     * @param Config $emailConfig The email template configuration service.
     */
    public function __construct(Config $emailConfig)
    {
        $this->emailConfig = $emailConfig;
    }

    /**
     * After plugin method to modify the options array of email templates.
     *
     * This method is called after the `toOptionArray()` method of the subject class to
     * append custom email templates to the existing options array.
     *
     * @param Template $subject The subject (instance of the email template source).
     * @param array $result The existing email template options array.
     *
     * @return array The modified options array with custom email templates.
     */
    public function afterToOptionArray(
        Template $subject,
        array $result,
    ): array {
        // Merge the existing options with the custom templates
        return Arr::merge($result, Arr::each([$this, 'getOptions'], self::AVAILABLE_EMAIL_TEMPLATES));
    }

    /**
     * Generate an option array for a specific email template ID.
     *
     * This method retrieves the label of the email template and constructs
     * the option array to be added to the email templates list.
     *
     * @param string $id The ID of the email template.
     *
     * @return array The option array containing 'label' and 'value' for the template.
     */
    public function getOptions(string $id): array
    {
        // Get the label of the email template using the email configuration
        $emailTemplateLabel = $this->emailConfig->getTemplateLabel($id);

        // Return the label and value as an array for the template option
        return [
            'label' => __($emailTemplateLabel),  // Translate the label
            'value' => $id,  // The template ID as the value
        ];
    }
}
