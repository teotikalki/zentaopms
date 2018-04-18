<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: bug.html.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    echo html::a(inlink('bug', "type=assignedTo"), "<span class='text'>{$lang->bug->assignedTo}</span>", '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=openedBy"),   "<span class='text'>{$lang->bug->openedBy}</span>",   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=resolvedBy"), "<span class='text'>{$lang->bug->resolvedBy}</span>", '', "class='btn btn-link" . ($type == 'resolvedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=closedBy"),   "<span class='text'>{$lang->bug->closedBy}</span>",   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
  <form class="main-table table-bug" data-ride="table" method="post" action='<?php echo $this->createLink('bug', 'batchEdit', "productID=0");?>'>
    <?php $canBatchEdit  = common::hasPriv('bug', 'batchEdit');?>
    <table class="table has-sort-head table-fixed". id='bugList'>
      <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <thead>
        <tr>
          <th class="w-100px">
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-severity'>  <?php common::printOrderLink('severity',   $orderBy, $vars, $lang->bug->severityAB);?></th>
          <th class='w-pri'>       <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
          <th class='w-type'>      <?php common::printOrderLink('type',       $orderBy, $vars, $lang->typeAB);?></th>
          <th>                     <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
          <th class='w-user'>      <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-user'>      <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->assignedTo);?></th>
          <th class='w-user'>      <?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
          <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
          <th class='w-160px'> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <tr>
          <td class="c-id">
            <div class="checkbox-primary">
              <?php if($canBatchEdit):?>
              <input type='checkbox' name='bugIDList[]' value='<?php echo $bug->id;?>' />
              <label></label>
              <?php endif;?>
              <?php printf('%03d', $bug->id);?>
            </div>
          </td>
          <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
          <td><?php echo zget($lang->bug->typeList, $bug->type, '');?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, null, "style='color: $bug->color'");?></td>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <td><?php echo zget($users, $bug->assignedTo);?></td>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <td><?php echo zget($lang->bug->resolutionList, $bug->resolution);?></td>
          <td class='c-actions'>
            <?php
            $params = "bugID=$bug->id";
            common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
            common::printIcon('bug', 'assignTo',   $params, '', 'list', 'hand-right', '', 'iframe', true);
            common::printIcon('bug', 'resolve',    $params, $bug, 'list', 'checked', '', 'iframe', true);
            common::printIcon('bug', 'close',      $params, $bug, 'list', 'off', '', 'iframe', true);
            common::printIcon('bug', 'edit',       $params, '', 'list');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($bugs):?>
    <div class="table-footer">
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        $actionLink = $this->createLink('bug', 'batchEdit');
        $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
        echo html::commonButton($lang->edit, $misc);
        ?>
        <?php
        if(common::hasPriv('bug', 'batchConfirm'))
        {
          $actionLink = $this->createLink('bug', 'batchConfirm');
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->confirmBug, $misc);
        }
        if(common::hasPriv('bug', 'batchClose'))
        {
          $actionLink = $this->createLink('bug', 'batchClose');
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->close, $misc);
        }
        ?>
        <?php
        $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
        if($canBatchAssignTo && count($bugs)):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->assignedTo?> <span class="caret"></span></button>
          <?php
          $withSearch = count($memberPairs) > 10;
          $actionLink = $this->createLink('bug', 'batchAssignTo', "productID=0&type=my");
          echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
          echo "<div class='dropdown-menu search-list' data-ride='searchList'>";
          if($withSearch)
          {
              echo '<div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">';
              echo '<input id="userSearchBox" type="search" class="form-control search-input" autocomplete="off" />';
              echo '<label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>';
              echo '<a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>';
              echo '</div>';
          }
          echo '<div class="list-group">';
          foreach ($memberPairs as $key => $value)
          {
              if(empty($key)) continue;
              echo html::a('javascript:$(".table-actions #assignedTo").val("' . $key . '");setFormAction("' . $actionLink . '")', '<i class="icon icon-person icon-sm"></i> ' . $value, '', "data-key='@$key'");
          }
          echo "</div>";
          echo "</div>";
          ?>
        </div>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php js::set('listName', 'bugList')?>
<?php include '../../common/view/footer.html.php';?>
