<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdDebugProjectContainerUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/gestion')) {
            if (0 === strpos($pathinfo, '/gestion/candidat')) {
                // candidat_index
                if (rtrim($pathinfo, '/') === '/gestion/candidat') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_candidat_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'candidat_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'candidat_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\CandidatController::indexAction',  '_route' => 'candidat_index',);
                }
                not_candidat_index:

                // candidat_show
                if (preg_match('#^/gestion/candidat/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_candidat_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'candidat_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'candidat_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\CandidatController::showAction',));
                }
                not_candidat_show:

                // candidat_new
                if ($pathinfo === '/gestion/candidat/new') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_candidat_new;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'candidat_new', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\CandidatController::newAction',  '_route' => 'candidat_new',);
                }
                not_candidat_new:

                // candidat_edit
                if (preg_match('#^/gestion/candidat/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_candidat_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'candidat_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'candidat_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\CandidatController::editAction',));
                }
                not_candidat_edit:

                // candidat_delete
                if (preg_match('#^/gestion/candidat/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'candidat_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'candidat_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\CandidatController::deleteAction',));
                }

            }

            if (0 === strpos($pathinfo, '/gestion/mairie')) {
                // mairie_index
                if (rtrim($pathinfo, '/') === '/gestion/mairie') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_mairie_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'mairie_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'mairie_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\MairieController::indexAction',  '_route' => 'mairie_index',);
                }
                not_mairie_index:

                // mairie_show
                if (preg_match('#^/gestion/mairie/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_mairie_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'mairie_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'mairie_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\MairieController::showAction',));
                }
                not_mairie_show:

                // mairie_new
                if ($pathinfo === '/gestion/mairie/new') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_mairie_new;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'mairie_new', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\MairieController::newAction',  '_route' => 'mairie_new',);
                }
                not_mairie_new:

                // mairie_edit
                if (preg_match('#^/gestion/mairie/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_mairie_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'mairie_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'mairie_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\MairieController::editAction',));
                }
                not_mairie_edit:

                // mairie_delete
                if (preg_match('#^/gestion/mairie/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_mairie_delete;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'mairie_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'mairie_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\MairieController::deleteAction',));
                }
                not_mairie_delete:

            }

            if (0 === strpos($pathinfo, '/gestion/election')) {
                // election_index
                if (rtrim($pathinfo, '/') === '/gestion/election') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_election_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'election_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'election_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\ElectionController::indexAction',  '_route' => 'election_index',);
                }
                not_election_index:

                // election_show
                if (preg_match('#^/gestion/election/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_election_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'election_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'election_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\ElectionController::showAction',));
                }
                not_election_show:

                // election_new
                if ($pathinfo === '/gestion/election/new') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_election_new;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'election_new', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\ElectionController::newAction',  '_route' => 'election_new',);
                }
                not_election_new:

                // election_edit
                if (preg_match('#^/gestion/election/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_election_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'election_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'election_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\ElectionController::editAction',));
                }
                not_election_edit:

                // election_delete
                if (preg_match('#^/gestion/election/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_election_delete;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'election_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'election_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\ElectionController::deleteAction',));
                }
                not_election_delete:

            }

            if (0 === strpos($pathinfo, '/gestion/qg')) {
                // qg_index
                if (rtrim($pathinfo, '/') === '/gestion/qg') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_qg_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'qg_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'qg_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\QgController::indexAction',  '_route' => 'qg_index',);
                }
                not_qg_index:

                // qg_show
                if (preg_match('#^/gestion/qg/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_qg_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'qg_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\QgController::showAction',));
                }
                not_qg_show:

                // qg_new
                if (rtrim($pathinfo, '/') === '/gestion/qg/new') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_qg_new;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'qg_new');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'qg_new', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\QgController::newAction',  '_route' => 'qg_new',);
                }
                not_qg_new:

                // qg_edit
                if (preg_match('#^/gestion/qg/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_qg_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'qg_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\QgController::editAction',));
                }
                not_qg_edit:

                // qg_delete
                if (preg_match('#^/gestion/qg/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_qg_delete;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'qg_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\QgController::deleteAction',));
                }
                not_qg_delete:

            }

            if (0 === strpos($pathinfo, '/gestion/bureau')) {
                // bureau_index
                if (rtrim($pathinfo, '/') === '/gestion/bureau') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_bureau_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'bureau_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\BureauController::indexAction',  '_route' => 'bureau_index',);
                }
                not_bureau_index:

                // bureau_show
                if (preg_match('#^/gestion/bureau/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_bureau_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\BureauController::showAction',));
                }
                not_bureau_show:

                // bureau_new
                if ($pathinfo === '/gestion/bureau/new') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_bureau_new;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_new', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\BureauController::newAction',  '_route' => 'bureau_new',);
                }
                not_bureau_new:

                // bureau_edit
                if (preg_match('#^/gestion/bureau/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_bureau_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\BureauController::editAction',));
                }
                not_bureau_edit:

                // bureau_delete
                if (preg_match('#^/gestion/bureau/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_bureau_delete;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\BureauController::deleteAction',));
                }
                not_bureau_delete:

            }

            if (0 === strpos($pathinfo, '/gestion/vote')) {
                // packvote_index
                if (rtrim($pathinfo, '/') === '/gestion/vote') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_packvote_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'packvote_index');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'packvote_index', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::indexAction',  '_route' => 'packvote_index',);
                }
                not_packvote_index:

                // packvote_show
                if (preg_match('#^/gestion/vote/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_packvote_show;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'packvote_show', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::showAction',));
                }
                not_packvote_show:

                if (0 === strpos($pathinfo, '/gestion/vote/new')) {
                    // packvote_new
                    if (preg_match('#^/gestion/vote/new/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_packvote_new;
                        }

                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'packvote_new', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_new')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::newbisAction',));
                    }
                    not_packvote_new:

                    // packvote_new_election
                    if (preg_match('#^/gestion/vote/new/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_packvote_new_election;
                        }

                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'packvote_new_election', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_new_election')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::newElectionsAction',));
                    }
                    not_packvote_new_election:

                }

                // packvote_edit
                if (preg_match('#^/gestion/vote/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_packvote_edit;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'packvote_edit', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::editAction',));
                }
                not_packvote_edit:

                // packvote_delete
                if (preg_match('#^/gestion/vote/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_packvote_delete;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'packvote_delete', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::deleteAction',));
                }
                not_packvote_delete:

                // packvote_deleteAllUltime
                if ($pathinfo === '/gestion/vote/deleteAllUltime') {
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'packvote_deleteAllUltime', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::remiseZeroPackVoteUltimeAction',  '_route' => 'packvote_deleteAllUltime',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/bureau')) {
            if (0 === strpos($pathinfo, '/bureau/c')) {
                if (0 === strpos($pathinfo, '/bureau/candidat')) {
                    // bureau_candidatPlus
                    if (0 === strpos($pathinfo, '/bureau/candidatPlus/bureau') && preg_match('#^/bureau/candidatPlus/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)/candidat/(?P<candidat>[^/]++)$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'bureau_candidatPlus', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_candidatPlus')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\BureauController::votePlusAction',));
                    }

                    // bureau_candidatMoins
                    if (0 === strpos($pathinfo, '/bureau/candidatMoins/bureau') && preg_match('#^/bureau/candidatMoins/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)/candidat/(?P<candidat>[^/]++)$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'bureau_candidatMoins', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_candidatMoins')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\BureauController::voteMoinsAction',));
                    }

                }

                // bureau_exportCsv
                if (0 === strpos($pathinfo, '/bureau/csv') && preg_match('#^/bureau/csv/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'bureau_exportCsv', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_exportCsv')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\BureauController::csvAction',));
                }

            }

            // bureau_utilisation_index
            if (rtrim($pathinfo, '/') === '/bureau') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_bureau_utilisation_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'bureau_utilisation_index');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'bureau_utilisation_index', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\BureauController::indexAction',  '_route' => 'bureau_utilisation_index',);
            }
            not_bureau_utilisation_index:

            // bureau_utilisation_index_bureau
            if (preg_match('#^/bureau/(?P<bureau>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_bureau_utilisation_index_bureau;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'bureau_utilisation_index_bureau', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_utilisation_index_bureau')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\BureauController::indexAvecBureauAction',));
            }
            not_bureau_utilisation_index_bureau:

        }

        if (0 === strpos($pathinfo, '/qg')) {
            // qg_utilisation_index
            if (preg_match('#^/qg/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_qg_utilisation_index;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'qg_utilisation_index', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_utilisation_index')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\QgController::indexAction',));
            }
            not_qg_utilisation_index:

            // qg_utilisation_definirip
            if (0 === strpos($pathinfo, '/qg/setNewIp') && preg_match('#^/qg/setNewIp/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_qg_utilisation_definirip;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'qg_utilisation_definirip', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_utilisation_definirip')), array (  '_controller' => 'AppBundle:Qg:setNewIp',));
            }
            not_qg_utilisation_definirip:

            // qg_utilisation_killall
            if (0 === strpos($pathinfo, '/qg/killall') && preg_match('#^/qg/killall/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'qg_utilisation_killall', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_utilisation_killall')), array (  '_controller' => 'AppBundle:Qg:killall',));
            }

            // qg_utilisation_delAllBureau
            if (0 === strpos($pathinfo, '/qg/delAllBureau') && preg_match('#^/qg/delAllBureau/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'qg_utilisation_delAllBureau', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'qg_utilisation_delAllBureau')), array (  '_controller' => 'AppBundle:Qg:remiseZeroBureau',));
            }

        }

        if (0 === strpos($pathinfo, '/mairie')) {
            // mairie_utilisation_index
            if (rtrim($pathinfo, '/') === '/mairie') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_mairie_utilisation_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'mairie_utilisation_index');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'mairie_utilisation_index', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\MairieController::indexAction',  '_route' => 'mairie_utilisation_index',);
            }
            not_mairie_utilisation_index:

            // mairie_utilisation_killall
            if ($pathinfo === '/mairie/killall') {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'mairie_utilisation_killall', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\MairieController::killallAction',  '_route' => 'mairie_utilisation_killall',);
            }

            // mairie_utilisation_delAllBureau
            if ($pathinfo === '/mairie/delAllBureau') {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'mairie_utilisation_delAllBureau', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\MairieController::remiseZeroBureauAction',  '_route' => 'mairie_utilisation_delAllBureau',);
            }

        }

        if (0 === strpos($pathinfo, '/utilisation/packhoraire')) {
            // utilisation_packhoraire_getlast
            if (0 === strpos($pathinfo, '/utilisation/packhoraire/bureau') && preg_match('#^/utilisation/packhoraire/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)/getLast$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_utilisation_packhoraire_getlast;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'utilisation_packhoraire_getlast', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_packhoraire_getlast')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\PackHoraireController::getLastParticipationAction',));
            }
            not_utilisation_packhoraire_getlast:

            // utilisation_packhoraire_voirgraph
            if (0 === strpos($pathinfo, '/utilisation/packhoraire/voirgraph/mairie') && preg_match('#^/utilisation/packhoraire/voirgraph/mairie/(?P<mairie>[^/]++)/election/(?P<election>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_utilisation_packhoraire_voirgraph;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'utilisation_packhoraire_voirgraph', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_packhoraire_voirgraph')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\PackHoraireController::voirGraphParticipationAction',));
            }
            not_utilisation_packhoraire_voirgraph:

            // utilisation_packhoraire_getfileparticipation
            if (0 === strpos($pathinfo, '/utilisation/packhoraire/getfileparticipation/mairie') && preg_match('#^/utilisation/packhoraire/getfileparticipation/mairie/(?P<mairie>[^/]++)/election/(?P<election>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_utilisation_packhoraire_getfileparticipation;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'utilisation_packhoraire_getfileparticipation', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_packhoraire_getfileparticipation')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\PackHoraireController::getFileParticipationAction',));
            }
            not_utilisation_packhoraire_getfileparticipation:

        }

        if (0 === strpos($pathinfo, '/gestion/packhoraire')) {
            // packhoraire_index
            if (rtrim($pathinfo, '/') === '/gestion/packhoraire') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_packhoraire_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'packhoraire_index');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_index', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::indexAction',  '_route' => 'packhoraire_index',);
            }
            not_packhoraire_index:

            // packhoraire_show
            if (preg_match('#^/gestion/packhoraire/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_packhoraire_show;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_show', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'packhoraire_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::showAction',));
            }
            not_packhoraire_show:

            // packhoraire_new
            if (0 === strpos($pathinfo, '/gestion/packhoraire/bureau') && preg_match('#^/gestion/packhoraire/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)/nb/(?P<nb>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_packhoraire_new;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_new', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'packhoraire_new')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::newBisAction',));
            }
            not_packhoraire_new:

            // packhoraire_edit
            if (preg_match('#^/gestion/packhoraire/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_packhoraire_edit;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_edit', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'packhoraire_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::editAction',));
            }
            not_packhoraire_edit:

            // packhoraire_delete
            if (preg_match('#^/gestion/packhoraire/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'DELETE') {
                    $allow[] = 'DELETE';
                    goto not_packhoraire_delete;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_delete', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'packhoraire_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::deleteAction',));
            }
            not_packhoraire_delete:

            // packhoraire_deleteAll
            if ($pathinfo === '/gestion/packhoraire/deleteAll') {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packhoraire_deleteAll', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackHoraireController::remiseZeroPackHoraireAction',  '_route' => 'packhoraire_deleteAll',);
            }

        }

        if (0 === strpos($pathinfo, '/utilisation')) {
            // packvote_delete_by_bureau
            if (0 === strpos($pathinfo, '/utilisation/vote/deleteAll/bureau') && preg_match('#^/utilisation/vote/deleteAll/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)$#s', $pathinfo, $matches)) {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'packvote_delete_by_bureau', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'packvote_delete_by_bureau')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PackVoteController::remiseZeroPackVoteAction',));
            }

            // bureau_nbInscrit
            if (0 === strpos($pathinfo, '/utilisation/detail/nbInscrit/bureau') && preg_match('#^/utilisation/detail/nbInscrit/bureau/(?P<bureau>[^/]++)/election/(?P<election>[^/]++)/nbinscrit/(?P<nbinscrit>[^/]++)$#s', $pathinfo, $matches)) {
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'bureau_nbInscrit', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'bureau_nbInscrit')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\DetailController::newNbInscritAction',));
            }

        }

        if (0 === strpos($pathinfo, '/visiteur')) {
            // visiteur_index
            if (rtrim($pathinfo, '/') === '/visiteur') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'visiteur_index');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'visiteur_index', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\VisiteurRController::indexAction',  '_route' => 'visiteur_index',);
            }

            if (0 === strpos($pathinfo, '/visiteur/show')) {
                // visiteur_showVoteDetail
                if (0 === strpos($pathinfo, '/visiteur/showVoteDetail') && preg_match('#^/visiteur/showVoteDetail/(?P<mairie>[^/]++)/election/(?P<election>[^/]++)/mode/(?P<mode>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_visiteur_showVoteDetail;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'visiteur_showVoteDetail', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'visiteur_showVoteDetail')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\VisiteurRController::showResultatDetailAction',));
                }
                not_visiteur_showVoteDetail:

                // visiteur_showParticipation
                if ($pathinfo === '/visiteur/showParticipation') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_visiteur_showParticipation;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'visiteur_showParticipation', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\VisiteurRController::showParticipationVoteAction',  '_route' => 'visiteur_showParticipation',);
                }
                not_visiteur_showParticipation:

            }

            // visiteur_denied
            if ($pathinfo === '/visiteur/denied') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_visiteur_denied;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'visiteur_denied', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\VisiteurRController::deniedAction',  '_route' => 'visiteur_denied',);
            }
            not_visiteur_denied:

        }

        if (0 === strpos($pathinfo, '/gestion/parti')) {
            // parti_index
            if (rtrim($pathinfo, '/') === '/gestion/parti') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_parti_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'parti_index');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'parti_index', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PartiController::indexAction',  '_route' => 'parti_index',);
            }
            not_parti_index:

            // parti_show
            if (preg_match('#^/gestion/parti/(?P<id>[^/]++)/show$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_parti_show;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'parti_show', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'parti_show')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PartiController::showAction',));
            }
            not_parti_show:

            // parti_new
            if ($pathinfo === '/gestion/parti/new') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_parti_new;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'parti_new', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\gestion\\PartiController::newAction',  '_route' => 'parti_new',);
            }
            not_parti_new:

            // parti_edit
            if (preg_match('#^/gestion/parti/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_parti_edit;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'parti_edit', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'parti_edit')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PartiController::editAction',));
            }
            not_parti_edit:

            // parti_delete
            if (preg_match('#^/gestion/parti/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'DELETE') {
                    $allow[] = 'DELETE';
                    goto not_parti_delete;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'parti_delete', key($requiredSchemes));
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'parti_delete')), array (  '_controller' => 'AppBundle\\Controller\\gestion\\PartiController::deleteAction',));
            }
            not_parti_delete:

        }

        if (0 === strpos($pathinfo, '/user')) {
            // utilisation_user_action
            if ($pathinfo === '/user/actions') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_utilisation_user_action;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'utilisation_user_action', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::indexAction',  '_route' => 'utilisation_user_action',);
            }
            not_utilisation_user_action:

            // utilisation_user_possession
            if ($pathinfo === '/user/possession') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_utilisation_user_possession;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'utilisation_user_possession', key($requiredSchemes));
                }

                return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::possessionAction',  '_route' => 'utilisation_user_possession',);
            }
            not_utilisation_user_possession:

            if (0 === strpos($pathinfo, '/user/role')) {
                // utilisation_user_droits
                if ($pathinfo === '/user/role') {
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'utilisation_user_droits', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::gererDroitAction',  '_route' => 'utilisation_user_droits',);
                }

                if (0 === strpos($pathinfo, '/user/role/gererDroit/user')) {
                    // utilisation_user_droits_bureau_ajax
                    if (preg_match('#^/user/role/gererDroit/user/(?P<user>[^/]++)/bureau/(?P<bureau>[^/]++)$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_droits_bureau_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_droits_bureau_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::gererDroitBureauAction',));
                    }

                    // utilisation_user_droits_qg_ajax
                    if (preg_match('#^/user/role/gererDroit/user/(?P<user>[^/]++)/qg/(?P<qg>[^/]++)$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_droits_qg_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_droits_qg_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::gererDroitQgAction',));
                    }

                    // utilisation_user_mairie_ajax
                    if (preg_match('#^/user/role/gererDroit/user/(?P<user>[^/]++)/mairie/(?P<mairie>[^/]++)$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_mairie_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_mairie_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::gererDroitMairieAction',));
                    }

                }

                if (0 === strpos($pathinfo, '/user/role/user/gererDroit/remove')) {
                    // utilisation_user_droits_bureau_remove_ajax
                    if (preg_match('#^/user/role/user/gererDroit/remove/(?P<user>[^/]++)/bureau$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_droits_bureau_remove_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_droits_bureau_remove_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::enleverDroitBureauAction',));
                    }

                    // utilisation_user_droits_qg_remove_ajax
                    if (preg_match('#^/user/role/user/gererDroit/remove/(?P<user>[^/]++)/qg$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_droits_qg_remove_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_droits_qg_remove_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::enleverDroitQgAction',));
                    }

                    // utilisation_user_droits_mairie_remove_ajax
                    if (preg_match('#^/user/role/user/gererDroit/remove/(?P<user>[^/]++)/mairie$#s', $pathinfo, $matches)) {
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'utilisation_user_droits_mairie_remove_ajax', key($requiredSchemes));
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'utilisation_user_droits_mairie_remove_ajax')), array (  '_controller' => 'AppBundle\\Controller\\utilisation\\UserController::enleverDroitMairieAction',));
                    }

                }

            }

        }

        // app_homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'app_homepage');
            }

            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$this->context->getScheme()])) {
                return $this->redirect($pathinfo, 'app_homepage', key($requiredSchemes));
            }

            return array (  '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  '_route' => 'app_homepage',);
        }

        if (0 === strpos($pathinfo, '/log')) {
            if (0 === strpos($pathinfo, '/login')) {
                // fos_user_security_login
                if ($pathinfo === '/login') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_fos_user_security_login;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_security_login', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::loginAction',  '_route' => 'fos_user_security_login',);
                }
                not_fos_user_security_login:

                // fos_user_security_check
                if ($pathinfo === '/login_check') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fos_user_security_check;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_security_check', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::checkAction',  '_route' => 'fos_user_security_check',);
                }
                not_fos_user_security_check:

            }

            // fos_user_security_logout
            if ($pathinfo === '/logout') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_security_logout;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'fos_user_security_logout', key($requiredSchemes));
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::logoutAction',  '_route' => 'fos_user_security_logout',);
            }
            not_fos_user_security_logout:

        }

        if (0 === strpos($pathinfo, '/profile')) {
            // fos_user_profile_show
            if (rtrim($pathinfo, '/') === '/profile') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_profile_show;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_profile_show');
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'fos_user_profile_show', key($requiredSchemes));
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ProfileController::showAction',  '_route' => 'fos_user_profile_show',);
            }
            not_fos_user_profile_show:

            // fos_user_profile_edit
            if ($pathinfo === '/profile/edit') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_profile_edit;
                }

                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$this->context->getScheme()])) {
                    return $this->redirect($pathinfo, 'fos_user_profile_edit', key($requiredSchemes));
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ProfileController::editAction',  '_route' => 'fos_user_profile_edit',);
            }
            not_fos_user_profile_edit:

        }

        if (0 === strpos($pathinfo, '/re')) {
            if (0 === strpos($pathinfo, '/register')) {
                // fos_user_registration_register
                if (rtrim($pathinfo, '/') === '/register') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_fos_user_registration_register;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'fos_user_registration_register');
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_registration_register', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::registerAction',  '_route' => 'fos_user_registration_register',);
                }
                not_fos_user_registration_register:

                if (0 === strpos($pathinfo, '/register/c')) {
                    // fos_user_registration_check_email
                    if ($pathinfo === '/register/check-email') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fos_user_registration_check_email;
                        }

                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$this->context->getScheme()])) {
                            return $this->redirect($pathinfo, 'fos_user_registration_check_email', key($requiredSchemes));
                        }

                        return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::checkEmailAction',  '_route' => 'fos_user_registration_check_email',);
                    }
                    not_fos_user_registration_check_email:

                    if (0 === strpos($pathinfo, '/register/confirm')) {
                        // fos_user_registration_confirm
                        if (preg_match('#^/register/confirm/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fos_user_registration_confirm;
                            }

                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$this->context->getScheme()])) {
                                return $this->redirect($pathinfo, 'fos_user_registration_confirm', key($requiredSchemes));
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'fos_user_registration_confirm')), array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::confirmAction',));
                        }
                        not_fos_user_registration_confirm:

                        // fos_user_registration_confirmed
                        if ($pathinfo === '/register/confirmed') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fos_user_registration_confirmed;
                            }

                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$this->context->getScheme()])) {
                                return $this->redirect($pathinfo, 'fos_user_registration_confirmed', key($requiredSchemes));
                            }

                            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::confirmedAction',  '_route' => 'fos_user_registration_confirmed',);
                        }
                        not_fos_user_registration_confirmed:

                    }

                }

            }

            if (0 === strpos($pathinfo, '/resetting')) {
                // fos_user_resetting_request
                if ($pathinfo === '/resetting/request') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_fos_user_resetting_request;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_resetting_request', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::requestAction',  '_route' => 'fos_user_resetting_request',);
                }
                not_fos_user_resetting_request:

                // fos_user_resetting_send_email
                if ($pathinfo === '/resetting/send-email') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fos_user_resetting_send_email;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_resetting_send_email', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::sendEmailAction',  '_route' => 'fos_user_resetting_send_email',);
                }
                not_fos_user_resetting_send_email:

                // fos_user_resetting_check_email
                if ($pathinfo === '/resetting/check-email') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_fos_user_resetting_check_email;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_resetting_check_email', key($requiredSchemes));
                    }

                    return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::checkEmailAction',  '_route' => 'fos_user_resetting_check_email',);
                }
                not_fos_user_resetting_check_email:

                // fos_user_resetting_reset
                if (0 === strpos($pathinfo, '/resetting/reset') && preg_match('#^/resetting/reset/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_fos_user_resetting_reset;
                    }

                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$this->context->getScheme()])) {
                        return $this->redirect($pathinfo, 'fos_user_resetting_reset', key($requiredSchemes));
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'fos_user_resetting_reset')), array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::resetAction',));
                }
                not_fos_user_resetting_reset:

            }

        }

        // fos_user_change_password
        if ($pathinfo === '/profile/change-password') {
            if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                goto not_fos_user_change_password;
            }

            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$this->context->getScheme()])) {
                return $this->redirect($pathinfo, 'fos_user_change_password', key($requiredSchemes));
            }

            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ChangePasswordController::changePasswordAction',  '_route' => 'fos_user_change_password',);
        }
        not_fos_user_change_password:

        if (0 === strpos($pathinfo, '/admin')) {
            // easyadmin
            if (rtrim($pathinfo, '/') === '/admin') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'easyadmin');
                }

                return array (  '_controller' => 'JavierEguiluz\\Bundle\\EasyAdminBundle\\Controller\\AdminController::indexAction',  '_route' => 'easyadmin',);
            }

            // admin
            if (rtrim($pathinfo, '/') === '/admin') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'admin');
                }

                return array (  '_controller' => 'JavierEguiluz\\Bundle\\EasyAdminBundle\\Controller\\AdminController::indexAction',  '_route' => 'admin',);
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
