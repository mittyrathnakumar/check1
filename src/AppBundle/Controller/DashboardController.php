<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\DashboardRepository;

class DashboardController extends Controller 
{
	
	/**
	 * @var ReportingRepository
	 */
	private $dashboardRepository;
	
	public function __construct() {			
		$this->dashboardRepository = new DashboardRepository();	
	}
	
	/**
	 * @Route("/Dashboard/WeeklyReport", name="WeeklyReport")
	 * @method({"GET","HEAD"})
	 */
	
	public function renderWeeklyReportAction(Request $request) {		

		/*$envRepository = new EnvironmentsRepository();
		$envList = $envRepository->getEnvironmentList();
		$envDetails = $envRepository->getEnvironmentDetails($env);
		
		return $this->render('Environments/NPEEnvironmentDetails.html.twig', [
				"env" => $env,
				"envList" => $envList,
				"envDetails" => $envDetails
		]);*/
		
		return $this->render('Dashboard/WeeklyReport.html.twig');
	}
	
	/**
	 * @Route("/Dashboard/CompletedProjects", name="CompletedProjects")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderCompletedProjectsAction(Request $request) {
		return $this->render('Dashboard/CompletedProjects.html.twig');
	}
	
	/**
	 * @Route("/Environments/MockServices", name="MockServices")
	 */
	public function renderMockServicesAction(Request $request) {
		return $this->render('Environments/MockServices.html.twig');
	}
	
}
