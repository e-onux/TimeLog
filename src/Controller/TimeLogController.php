<?php

namespace App\Controller;

use App\Entity\TimeLog;
use App\Form\TimeLogType;
use App\Repository\TimeLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/time/log")
 */
class TimeLogController extends AbstractController
{
    /**
     * @Route("/", name="time_log_index", methods={"GET"})
     */
    public function index(TimeLogRepository $timeLogRepository): Response
    {

        $latest_time_log = $timeLogRepository->findBy(array(),array('id'=>'DESC'),1,0);
        return $this->render('time_log/index.html.twig', [
            'time_logs' => $timeLogRepository->findAll(),
            'latest' => reset($latest_time_log),
        ]);
    }

    /**
     * @Route("/start", name="time_log_start", methods={"GET"})
     */
    public function start(TimeLogRepository $timeLogRepository): Response
    {

        $latest_time_log =  $timeLogRepository->findBy(array(),array('id'=>'DESC'),1,0);
        $latest_time_log = reset($latest_time_log);
        if($latest_time_log&&!$latest_time_log->getEndTime()){
            throw new \Exception("A work has been already started! You must stop it first.");
        }

        $entityManager = $this->getDoctrine()->getManager();

        $time_log = new TimeLog();
        $time_log->setStartTime(new \DateTime());
        $time_log->setWorkDay(new \DateTime());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($time_log);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->redirect($this->generateUrl('time_log_index'));
    }

    /**
     * @Route("/end", name="time_log_end", methods={"GET"})
     */
    public function end(TimeLogRepository $timeLogRepository): Response
    {
        $latest_time_log = $timeLogRepository->findBy(array(),array('id'=>'DESC'),1,0);
        $latest_time_log = reset($latest_time_log);
        if(!$latest_time_log||!is_null($latest_time_log->getEndTime())){
            throw new \Exception("You must start a new work first.");
        }
        $entityManager = $this->getDoctrine()->getManager();

        $latest_time_log->setEndTime(new \DateTime());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($latest_time_log);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirect($this->generateUrl('time_log_index'));
    }

    /**
     * @Route("/new", name="time_log_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $timeLog = new TimeLog();
        $form = $this->createForm(TimeLogType::class, $timeLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($timeLog);
            $entityManager->flush();

            return $this->redirectToRoute('time_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_log/new.html.twig', [
            'time_log' => $timeLog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="time_log_show", methods={"GET"})
     */
    public function show(TimeLog $timeLog): Response
    {
        return $this->render('time_log/show.html.twig', [
            'time_log' => $timeLog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="time_log_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TimeLog $timeLog): Response
    {
        $form = $this->createForm(TimeLogType::class, $timeLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('time_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_log/edit.html.twig', [
            'time_log' => $timeLog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="time_log_delete", methods={"POST"})
     */
    public function delete(Request $request, TimeLog $timeLog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeLog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($timeLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('time_log_index', [], Response::HTTP_SEE_OTHER);
    }
}
