<?

class View_Helper_Dashmenu
    extends Zend_View_Helper_Abstract
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ dashmenu @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 * @return string
 */
    public function dashmenu () {
	//@TODO: test for no user?
	//@TODO: $page should be set from subpage context
	//@TODO: refactor menu for Zend_Navigation, config file. 

        $user = CPF_Config::getInstance()->current_user();
	extract($user->toArray()); // dumps all fields into local variables. 


        $num_prospects  = $user->num_prospects();
        $costate = $user->get_company_state();
	$verify = $user->verify();

	ob_start();
// no page :: default = dash
            //******first part menu starts********//
        ?>
<div id="nav"><!-- start nav -->
<ul>
 <?

        $rebate_forms_title = 'Rebate Forms';
        global $is_in_rebate;
        if(isset($is_in_rebate) && $is_in_rebate) $rebate_forms_title = "Rebate Forms <img src=\"/images/new.gif\" />";

//        $page_url = "/formfill/forms/user/fromproposal/userid/$userid/verify/$verify/fromproposal/1";
        $page_url = "/formfill/?rand=" . rand(0, 10000);
//        if(isset($is_in_rebate) && $is_in_rebate) $page_url = "";

        $menu = array(
                    '' => 'Dashboard',
                    'costmodel' => 'Quote Modeling',
                    'mrp' => 'MRP',
                    'utilities' => 'Utilities &amp; Rates',
                    'incentives' => 'Incentives',
                    'forms' => array(
                            'title' => $rebate_forms_title,
                            'page_url' => $page_url,
							'isnew' => 'yes'),
                    'template' => 'Proposal Templates',
                    'reports' => 'Reports'
             );

        // if (!in_array($userid, Row_Jobs::$beta_users)):
		 $states = CPF_Config::cpf_get()->formset->states->toArray();
		 if (!$user->has_formsets() && !in_array($user->my_admin()->state, $states)):
				// This is a NEGATION method --
				// if the user has no formsets and they are not "in state"
				// get rid of the formset menu.
			unset($menu['forms']);
         endif;

/*
 * This is a test to eliminate the incentive menu -- if it fails the menu is erased.
 * if you are on stage, it always succeeds. (strcasecmp is a "strings are diffeerent" function)
 * If you aren't, if you are NOT using the JN+9 account
 *   AND your account is NOT either an admin or a granted account,
 *   the test also fails.
 */

        if($user && (($atype == 'A') || $ccincents)):
                $keep_incentives = TRUE;
        else:
                $keep_incentives = FALSE;
        endif;

        if (!$keep_incentives):
                unset($menu['incentives']);
        endif;

        foreach($menu as $key => $title):
                if (isset($page_url)):
                        unset($page_url);
                endif;
				if (isset($isnew)):
					unset($isnew);
				endif;
                if (is_array($title)):
                        extract($title); // page_url will come from here. 
                endif;

                if(!isset($page) && $is_in_rebate) $page = "forms";

                if ($key == $page):
                        echo '<li>&raquo;&nbsp;', $title, '</li>';
                elseif (isset($page_url)):
                        echo '<li>', '<a href="' . $page_url . '" >', $title, '</a>';
                else:
                        echo '<li>', '<a href="/secure/dashboard.php?page=' . $key . "&userid=$userid&verify=$verify" . '">', $title, '</a>';
                endif;

				if (isset($isnew) && ($key != $page)): ?>
    <img onMouseover="ddrivetip('<p>Rebate Forms help you automatically generate the paperwork needed to receive rebates. Give it a try now, your first few Jobs are free!</p><p>Click to the left to generate Rebate Forms for Jobs you have already marked as Sold. To generate Rebate Forms for a Job on your Dashboard, just go to the Proposal tab and click on the link there.</p>')"
    onMouseout="hideddrivetip()" src="/images/new.gif"></li>
<?
				else:
					echo '</li>';
				endif;

        endforeach; // menus
        //******first part menu ends********//

        //******second part menu starts*********//
?>
</ul>
    <div id="leftbar"><!-- start submenu -->
<form action="/secure/estimate.php" method="post">
<input type="hidden" name="action" value="newjob">
<input type="hidden" name="userid" value="$userid">
<input type="hidden" name="verify" value="$verify">
<input type="hidden" name="coid" value="$coid">
 <?
        if ($costate == "expired" OR $costate == "canceled") {
          if ($costate == "expired") $newjobmsg = "Free trial has expired";
          else $newjobmsg = "Subscription suspended";
          if($atype=="A") {
            ?>
</form><div class="singlered"><span style="color:red; font-weight:bold;">New Jobs disabled</span><br />$newjobmsg<br /><br /><a href="dashboard.php?page=priviledges&subpage=payments&action=cart&userid=$userid&verify=$verify">Click here to subscribe</a></div><br />
 <?
          } else {
            ?>
</form><div class="singlered"><span style="color:red; font-weight:bold;">New Jobs disabled</span><br />$newjobmsg<br /><br />Please contact 
 <?
            if ($afirstname == "") {
              ?>
your Administrator or <a href="mailto:help.tools@CleanPowerFinance.com">email us</a> for assistance.</div><br />
 <?
            } else {
              ?>
<a href="mailto:$username">$afirstname $alastname</a> or <a href="mailto:help.tools@CleanPowerFinance.com">email us</a> for assistance.</div><br />
 <?
            }
          } // endif user is type U
        } else { // company is in free trial or paid
          if($atype=="A") {
            if ($costate == "paid") {
              ?>
<div class="doublelightblue"><input type="submit" class="button" value="Add a New Job"></div></form>
 <?

              // List Number of Leads needing attention
              if($num_prospects > 0) {
                ?>
<div class="doublered" style="text-align:center;">
 
<b>Sales Prospects<br />$num_prospects New</b> 
 
<a href="?page=leads&userid=$userid&verify=$verify">&raquo; View</a>
 
</div>
 <?
              }

            } else { // if not expired or paid, costate is num of days into trial
              ?>
<div class="doublelightblue" style="text-align:center;"><input type="submit" class="button" value="Add a New Job"><br /><br /><span style="color:red; font-weight:bold; font-size:12;">Day $costate of free trial</span><br /><a href="dashboard.php?page=priviledges&subpage=payments&action=cart&userid=$userid&verify=$verify"><span style="color:red; font-weight:normal; text-decoration:underline; font-size:10;">Click to subscribe now</span></a></div></form>
 <?
            }
          } else { // user of type U
            if ($active == "1") {
              if ($costate == "paid") {
?>
<div class="doublelightblue"><input type="submit" class="button" value="Add a New Job"></div></form>
<?
 // List Number of Leads needing attention
if($num_prospects > 0) {
?>
<div class="doublered" style="text-align:center;">
 
<b>Sales Prospects<br />$num_prospects New</b> 
 
<a href="?page=leads&userid=$userid&verify=$verify">&raquo; View</a>
 
</div>
 <?
                }
              } else { // if not expired or paid, costate is num of days into trial
                ?>
<div class="doublelightblue" style="text-align:center;"><input type="submit" class="button" value="Add a New Job"><br />
    <br />
    <span style="color:red; font-weight:bold; font-size:12;">Day $costate of free trial</span><br />
    <span style="font-weight:normal; font-size:10;">
	To subscribe, please contact
<a href="mailto:<?= $this->admin_username($user) ?>"><?= $this->admin_name($user) ?></a>.</span></div></form>
 <?
                
              } // endif acct is not paid
            } else { // account is inactive
              ?>
</form><div class="singlered"><span style="color:red; font-weight:bold;">New Jobs disabled</span><br />Your account has been deactivated<br /><br />Please contact 
 <?
              if ($afirstname == "") {
                ?>
your Administrator.</span></div></form>
 <?
              } else {
                ?>
<a href="mailto:$username">$afirstname $alastname</a>.</span></div></form>
 <?
              }
            } // endif account is inactive
          } // endif user is type U
        } // company is in free trial or paid

        ?>
</form><!-- 219 -->
 <?

       // end if page != subscription, leads, or loans

          // Webmaster Messages
          if(strlen($adminmsg) > 5) {
            ?>
<div class="doubleblue">$adminmsg</div>
 <?
          }

	  echo $this->marketing_messages();

          //***********second part menu ends**************//
?>
</div><!-- end subnav -->
</div><!-- end nav -->
<?
    return ob_get_clean();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ admin_user_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_Users $pUser
     * @return <type>
     */
    public function admin_user_name (Model_Users $pUser) {
	return $pUser->my_admin()->get_username();
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ admin_name @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     * @param Model_Users $pUser
     * @return string
     */
    public function admin_name (Model_Users $pUser) {
	$name = trim($pUser->my_admin()->as_person()->name());
	if (!$name) $name = 'Your Administrator';
	return $name;
    }

    public function marketing_messages() {
	ob_start();
	$messages = Model_Mktgmsgs::getInstance()->find_all('msg_order');

	if(count($messages)):
	    foreach($messages as $mnum => $message_object):
		extract($message_object->toArray());
		$div_class = ($mnum % 2) ? "doubleorange" : "doublegreen";
		
?>
    <div class="<?= $div_class ?>" />
<? if ($link_on_image): ?><a target="_blank" href="<?= $link_on_image ?>" >
<? endif; ?>
<? if ($image_name):    ?><img alt="$link_on_image" src="/marketing/mktg_images/<?= $image_name ?>" />
<? endif; ?>
<? if($link_on_image):  ?></a ><br />
<? endif; ?>
	    <?= $message //show marketing message ?>
    </div>
<? 
	    endforeach;
	endif; // count $messages
	return ob_get_clean();
    }

}