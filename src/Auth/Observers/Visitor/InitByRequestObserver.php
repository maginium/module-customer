<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Observers\Visitor;

use Magento\Customer\Model\Visitor;
use Magento\Customer\Observer\Visitor\AbstractVisitorObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class InitByRequestObserver.
 *
 * Observer for initializing the visitor data based on the current request.
 * This observer handles setting session-level data and delegates request initialization to the visitor model.
 */
class InitByRequestObserver extends AbstractVisitorObserver implements ObserverInterface
{
    /**
     * @var SessionManager Handles session management for storing visitor data.
     */
    private SessionManagerInterface $sessionManager;

    /**
     * Constructor.
     *
     * @param Visitor $visitor The visitor model responsible for visitor-related operations.
     * @param SessionManagerInterface $sessionManager Manages session-level visitor data.
     */
    public function __construct(
        Visitor $visitor,
        SessionManagerInterface $sessionManager,
    ) {
        parent::__construct($visitor);

        $this->sessionManager = $sessionManager;
    }

    /**
     * Execute observer logic.
     *
     * Initializes the visitor data using the current request and sets session-level metadata.
     *
     * @param Observer $observer The event observer containing event data.
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        // Set session data indicating the customer login process.
        $this->sessionManager->setVisitorData(['do_customer_login' => true]);

        // Initialize visitor data based on the current request.
        $this->visitor->initByRequest($observer);
    }
}
