<?php

namespace app\controllers;

use app\application\Application;
use app\forms\BankingProductFilterForm;
use app\forms\CustomerSearchForm;
use app\helpers\PaginationHelper;
use app\services\CustomerService;
use app\services\ServiceService;

class CustomerController extends Controller {
    public function name(): string {
        return 'Customer';
    }

    public function actionSearch() {
        $form = new CustomerSearchForm();
        if ($form->load($_POST) && $form->validate()) {
            if (null !== $customer = (new CustomerService())->findByPassport($form->passport)) {
                return $this->redirect(DIRECTORY_SEPARATOR . 'customer' . DIRECTORY_SEPARATOR . 'info', ['id' => $customer->id]);
            } else {
                return $this->render('search', ['form' => $form, 'doesNotExist' => true]);
            }
        }
        return $this->render('search', ['form' => $form]);
    }

    public function actionInfo(string $id = '', int $p = 0, ...$formParameters) {
        $customer = (new CustomerService())->findById($id);
        if (!isset($customer)) {
            return $this->redirect(DIRECTORY_SEPARATOR . 'customer' . DIRECTORY_SEPARATOR . 'search');
        }
        $filterForm = new BankingProductFilterForm();
        if ($filterForm->load($_GET) && $filterForm->validate()) {
            $from = $filterForm->dateFrom;
            $till = $filterForm->dateTill;
            $status = $filterForm->status;
            $accountNumber = $filterForm->accountNumber;
        } else {
            $from = $till = $status = $accountNumber = null;
        }
        $serviceService = new ServiceService();
        $helper = new PaginationHelper($serviceService->getRecordsCountByCustomerIdForCustomerAndFilter($id, $from, $till, $status, $accountNumber), $p, Application::$maxRecordsPerPage);
        $services = $serviceService->findAllByCustomerIdForCustomerAndFilter($id, $from, $till, $status, $accountNumber, $helper->getStartRecordIndex(), $helper->getEndRecordIndex());
        return $this->render('info', [
            'customer' => $customer,
            'services' => $services,
            'paginationHelper' => $helper,
            'appendFormParams' => ['id' => $id],
            'appendPaginationParams' => $formParameters + ['id' => $id],
            'form' => $filterForm]);
    }
}
