<?php

namespace console\controllers;

use common\components\ProfileRule;
use common\components\UpdateProfileRule;
use Yii;
use yii\console\Controller;
use common\components\OwnerRule;
use common\components\DepartmentRule;

/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = Yii::$app->authManager;

        $ownerRule = new OwnerRule();
        $departmentRule = new DepartmentRule();
        $profileRule = new ProfileRule();
        $updateProfileRule = new UpdateProfileRule();

        $authManager->add($ownerRule);
        $authManager->add($departmentRule);
        $authManager->add($profileRule);
        $authManager->add($updateProfileRule);

        /*
         * Creating Permissions
         */

        // News
        $create_news = $authManager->createPermission('create_news');
        $update_news = $authManager->createPermission('update_news');
        $delete_news = $authManager->createPermission('delete_news');

        $create_news->description = 'Admins can create news in their department';
        $update_news->description = 'Admins can update news in their department';
        $delete_news->description = 'Admins can delete news in their department';

        $create_news->ruleName = $departmentRule->name;
        $update_news->ruleName = $departmentRule->name;
        $delete_news->ruleName = $departmentRule->name;

        // Instructions
        $create_instructions = $authManager->createPermission('create_instructions');
        $update_instructions = $authManager->createPermission('update_instructions');
        $delete_instructions = $authManager->createPermission('delete_instructions');

        $create_instructions->description = 'Admins can create instructions in their department';
        $update_instructions->description = 'Admins can update instructions in their department';
        $delete_instructions->description = 'Admins can delete instructions in their department';

        $create_instructions->ruleName = $departmentRule->name;
        $update_instructions->ruleName = $departmentRule->name;
        $delete_instructions->ruleName = $departmentRule->name;

        // Educations
        $create_educations = $authManager->createPermission('create_educations');
        $update_educations = $authManager->createPermission('update_educations');
        $update_own_educations = $authManager->createPermission('update_own_educations');
        $delete_educations = $authManager->createPermission('delete_educations');
        $delete_own_educations = $authManager->createPermission('delete_own_educations');
        $view_educations = $authManager->createPermission('view_educations');

        $create_educations->description = 'Admins and experts can create educations in their department';
        $update_educations->description = 'Admins can update educations in their department';
        $update_own_educations->description = 'Experts can update their own educations in their department';
        $delete_educations->description = 'Admins can delete educations in their department';
        $delete_own_educations->description = 'Experts can delete their own educations in their department';
        $view_educations->description = 'All signed in users can view educations in their department';

        $create_educations->ruleName = $departmentRule->name;
        $update_educations->ruleName = $departmentRule->name;
        $delete_educations->ruleName = $departmentRule->name;
        $update_own_educations->ruleName = $ownerRule->name;
        $delete_own_educations->ruleName = $ownerRule->name;

        // Orders
        $create_orders = $authManager->createPermission('create_orders');
        $update_orders = $authManager->createPermission('update_orders');
        $update_own_orders = $authManager->createPermission('update_own_orders');
        $cancel_orders = $authManager->createPermission('cancel_orders');
        $cancel_own_orders = $authManager->createPermission('cancel_own_orders');
        $accept_orders = $authManager->createPermission('accept_orders');
        $cancel_accept_orders = $authManager->createPermission('cancel_accept_orders');
        $cancel_own_accept_orders = $authManager->createPermission('cancel_own_accept_orders');
        $suspend_accept_orders = $authManager->createPermission('suspend_accept_orders');
        $suspend_orders = $authManager->createPermission('suspend_orders');

        $create_orders->description = 'Users can create a new order in one of departments';
        $update_orders->description = 'Master can update orders';
        $update_own_orders->description = 'Users can update their own order in departments';
        $cancel_orders->description = 'Master can cancel orders';
        $cancel_own_orders->description = 'Users can cancel their own order in departments';
        $accept_orders->description = 'Experts can accept an order in their department';
        $cancel_accept_orders->description = 'Master can cancel acceptance of an order';
        $cancel_own_accept_orders->description = 'Experts can cancel their acceptance of an order in their department';
        $suspend_accept_orders->description = 'Admins can suspend an order acceptance in their department';
        $suspend_orders->description = 'Admins can suspend an order in their department';

        $update_own_orders->ruleName = $ownerRule->name;
        $cancel_own_orders->ruleName = $ownerRule->name;
        $accept_orders->ruleName = $departmentRule->name;
        $cancel_own_accept_orders->ruleName = $ownerRule->name;
        $suspend_accept_orders->ruleName = $departmentRule->name;
        $suspend_orders->ruleName = $departmentRule->name;

        // Tickets
        $create_tickets = $authManager->createPermission('create_tickets');
        $response_tickets = $authManager->createPermission('response_tickets');
        $response_own_tickets = $authManager->createPermission('response_own_tickets');
        $suspend_tickets = $authManager->createPermission('suspend_tickets');
        $reopen_tickets = $authManager->createPermission('reopen_tickets');
        $reopen_own_tickets = $authManager->createPermission('reopen_own_tickets');

        $create_tickets->description = 'Users can create a ticket in one of departments';
        $response_tickets->description = 'Experts can respond to a ticket in their departments';
        $response_own_tickets->description = 'Users can respond to their own ticket in a department';
        $suspend_tickets->description = 'Admins and experts can suspend a ticket in their department';
        $reopen_tickets->description = 'Admins can reopen a suspended ticket in their department';
        $reopen_own_tickets->description = 'Users can reopen their ticket in a department';

        $response_tickets->ruleName = $departmentRule->name;
        $response_own_tickets->ruleName = $ownerRule->name;
        $suspend_tickets->ruleName = $departmentRule->name;
        $reopen_tickets->ruleName = $departmentRule->name;
        $reopen_own_tickets->ruleName = $ownerRule->name;

        // Profile
        $view_profile = $authManager->createPermission('view_profile');
        $update_profile = $authManager->createPermission('update_profile');
        $update_own_profile = $authManager->createPermission('update_own_profile');

        $view_profile->description = 'Users can only view expert profiles that accepted one of their orders';
        $update_profile->description = 'Master can update every one profiles';
        $update_own_profile->description = 'All can update their own profiles';

        $view_profile->ruleName = $profileRule->name;
        $update_own_profile->ruleName = $updateProfileRule->name;

        // Adding
        $authManager->add($create_news);
        $authManager->add($update_news);
        $authManager->add($delete_news);

        $authManager->add($create_instructions);
        $authManager->add($update_instructions);
        $authManager->add($delete_instructions);

        $authManager->add($create_educations);
        $authManager->add($update_educations);
        $authManager->add($update_own_educations);
        $authManager->add($delete_educations);
        $authManager->add($delete_own_educations);
        $authManager->add($view_educations);

        $authManager->add($create_orders);
        $authManager->add($update_orders);
        $authManager->add($update_own_orders);
        $authManager->add($cancel_orders);
        $authManager->add($cancel_own_orders);
        $authManager->add($accept_orders);
        $authManager->add($cancel_accept_orders);
        $authManager->add($cancel_own_accept_orders);
        $authManager->add($suspend_accept_orders);
        $authManager->add($suspend_orders);

        $authManager->add($create_tickets);
        $authManager->add($response_tickets);
        $authManager->add($response_own_tickets);
        $authManager->add($suspend_tickets);
        $authManager->add($reopen_tickets);
        $authManager->add($reopen_own_tickets);

        $authManager->add($view_profile);
        $authManager->add($update_profile);
        $authManager->add($update_own_profile);

        /**
         * Creating Roles
         */

        // Master
        $master = $authManager->createRole('master');
        $master->description = 'One who can control everything';
        $authManager->add($master);

        // Admins
        $admin = $authManager->createRole('admin');
        $admin->description = 'This is administrator of a department who controls all actions there';
        $authManager->add($admin);

        // Experts
        $expert = $authManager->createRole('expert');
        $expert->description = 'This is an expert in a department who can do works there';
        $authManager->add($expert);

        // Users
        $user = $authManager->createRole('user');
        $user->description = 'This is a normal user who can ask for services';
        $authManager->add($user);

        /**
         * Assignments
         */

        // Permissions
        $authManager->addChild($update_own_educations, $update_educations);
        $authManager->addChild($delete_own_educations, $delete_educations);
        $authManager->addChild($update_own_orders, $update_orders);
        $authManager->addChild($cancel_own_orders, $cancel_orders);
        $authManager->addChild($cancel_own_accept_orders, $cancel_accept_orders);
        $authManager->addChild($response_own_tickets, $response_tickets);
        $authManager->addChild($reopen_own_tickets, $reopen_tickets);
        $authManager->addChild($update_own_profile, $update_profile);

        // Master
        $authManager->addChild($master, $create_news);
        $authManager->addChild($master, $update_news);
        $authManager->addChild($master, $delete_news);

        $authManager->addChild($master, $create_instructions);
        $authManager->addChild($master, $update_instructions);
        $authManager->addChild($master, $delete_instructions);

        $authManager->addChild($master, $create_educations);
        $authManager->addChild($master, $update_educations);
        $authManager->addChild($master, $delete_educations);
        $authManager->addChild($master, $view_educations);

        $authManager->addChild($master, $create_orders);
        $authManager->addChild($master, $update_orders);
        $authManager->addChild($master, $cancel_orders);
        $authManager->addChild($master, $accept_orders);
        $authManager->addChild($master, $cancel_accept_orders);
        $authManager->addChild($master, $suspend_accept_orders);
        $authManager->addChild($master, $suspend_orders);

        $authManager->addChild($master, $create_tickets);
        $authManager->addChild($master, $response_tickets);
        $authManager->addChild($master, $suspend_tickets);
        $authManager->addChild($master, $reopen_tickets);

        $authManager->addChild($master, $view_profile);
        $authManager->addChild($master, $update_profile);

        // Admins
        $authManager->addChild($admin, $create_news);
        $authManager->addChild($admin, $update_news);
        $authManager->addChild($admin, $delete_news);

        $authManager->addChild($admin, $create_instructions);
        $authManager->addChild($admin, $update_instructions);
        $authManager->addChild($admin, $delete_instructions);

        $authManager->addChild($admin, $create_educations);
        $authManager->addChild($admin, $update_educations);
        $authManager->addChild($admin, $delete_educations);
        $authManager->addChild($admin, $view_educations);

        $authManager->addChild($admin, $suspend_accept_orders);
        $authManager->addChild($admin, $suspend_orders);

        $authManager->addChild($admin, $response_tickets);
        $authManager->addChild($admin, $suspend_tickets);
        $authManager->addChild($admin, $reopen_tickets);

        $authManager->addChild($admin, $view_profile);
        $authManager->addChild($admin, $update_own_profile);

        // Experts
        $authManager->addChild($expert, $create_educations);
        $authManager->addChild($expert, $update_own_educations);
        $authManager->addChild($expert, $delete_own_educations);
        $authManager->addChild($expert, $view_educations);

        $authManager->addChild($expert, $accept_orders);
        $authManager->addChild($expert, $cancel_own_accept_orders);

        $authManager->addChild($expert, $response_tickets);
        $authManager->addChild($expert, $suspend_tickets);

        $authManager->addChild($expert, $view_profile);
        $authManager->addChild($expert, $update_own_profile);
        
        // Users
        $authManager->addChild($user, $view_educations);
        
        $authManager->addChild($user, $create_orders);
        $authManager->addChild($user, $update_own_orders);
        $authManager->addChild($user, $cancel_own_orders);

        $authManager->addChild($user, $create_tickets);
        $authManager->addChild($user, $response_own_tickets);
        $authManager->addChild($user, $reopen_own_tickets);

        $authManager->addChild($user, $view_profile);
        $authManager->addChild($user, $update_own_profile);
    }
}