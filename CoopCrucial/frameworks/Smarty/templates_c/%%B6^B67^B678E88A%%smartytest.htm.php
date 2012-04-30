<?php /* Smarty version 2.6.18, created on 2007-09-12 13:12:17
         compiled from smartytest.htm */ ?>
<html>
	<head>
		<title>Smarty</title>
	</head>
<body>
	<?php echo $this->_tpl_vars['nofile']; ?>

	<h2><?php echo $this->_tpl_vars['filename']; ?>
</h2>
	<table border = 3 bordercolor="#FF0000" bgcolor="gray" align="center">
	<?php unset($this->_sections['cvs']);
$this->_sections['cvs']['name'] = 'cvs';
$this->_sections['cvs']['loop'] = is_array($_loop=$this->_tpl_vars['dato']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cvs']['show'] = true;
$this->_sections['cvs']['max'] = $this->_sections['cvs']['loop'];
$this->_sections['cvs']['step'] = 1;
$this->_sections['cvs']['start'] = $this->_sections['cvs']['step'] > 0 ? 0 : $this->_sections['cvs']['loop']-1;
if ($this->_sections['cvs']['show']) {
    $this->_sections['cvs']['total'] = $this->_sections['cvs']['loop'];
    if ($this->_sections['cvs']['total'] == 0)
        $this->_sections['cvs']['show'] = false;
} else
    $this->_sections['cvs']['total'] = 0;
if ($this->_sections['cvs']['show']):

            for ($this->_sections['cvs']['index'] = $this->_sections['cvs']['start'], $this->_sections['cvs']['iteration'] = 1;
                 $this->_sections['cvs']['iteration'] <= $this->_sections['cvs']['total'];
                 $this->_sections['cvs']['index'] += $this->_sections['cvs']['step'], $this->_sections['cvs']['iteration']++):
$this->_sections['cvs']['rownum'] = $this->_sections['cvs']['iteration'];
$this->_sections['cvs']['index_prev'] = $this->_sections['cvs']['index'] - $this->_sections['cvs']['step'];
$this->_sections['cvs']['index_next'] = $this->_sections['cvs']['index'] + $this->_sections['cvs']['step'];
$this->_sections['cvs']['first']      = ($this->_sections['cvs']['iteration'] == 1);
$this->_sections['cvs']['last']       = ($this->_sections['cvs']['iteration'] == $this->_sections['cvs']['total']);
?>
	<tr>
    <?php unset($this->_sections['cvs1']);
$this->_sections['cvs1']['name'] = 'cvs1';
$this->_sections['cvs1']['loop'] = is_array($_loop=$this->_tpl_vars['dato'][$this->_sections['cvs']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cvs1']['show'] = true;
$this->_sections['cvs1']['max'] = $this->_sections['cvs1']['loop'];
$this->_sections['cvs1']['step'] = 1;
$this->_sections['cvs1']['start'] = $this->_sections['cvs1']['step'] > 0 ? 0 : $this->_sections['cvs1']['loop']-1;
if ($this->_sections['cvs1']['show']) {
    $this->_sections['cvs1']['total'] = $this->_sections['cvs1']['loop'];
    if ($this->_sections['cvs1']['total'] == 0)
        $this->_sections['cvs1']['show'] = false;
} else
    $this->_sections['cvs1']['total'] = 0;
if ($this->_sections['cvs1']['show']):

            for ($this->_sections['cvs1']['index'] = $this->_sections['cvs1']['start'], $this->_sections['cvs1']['iteration'] = 1;
                 $this->_sections['cvs1']['iteration'] <= $this->_sections['cvs1']['total'];
                 $this->_sections['cvs1']['index'] += $this->_sections['cvs1']['step'], $this->_sections['cvs1']['iteration']++):
$this->_sections['cvs1']['rownum'] = $this->_sections['cvs1']['iteration'];
$this->_sections['cvs1']['index_prev'] = $this->_sections['cvs1']['index'] - $this->_sections['cvs1']['step'];
$this->_sections['cvs1']['index_next'] = $this->_sections['cvs1']['index'] + $this->_sections['cvs1']['step'];
$this->_sections['cvs1']['first']      = ($this->_sections['cvs1']['iteration'] == 1);
$this->_sections['cvs1']['last']       = ($this->_sections['cvs1']['iteration'] == $this->_sections['cvs1']['total']);
?>
	  <td><?php echo $this->_tpl_vars['dato'][$this->_sections['cvs']['index']][$this->_sections['cvs1']['index']]; ?>
</td>
	<?php endfor; endif; ?>	  
	</tr>
	<?php endfor; endif; ?>
	</table>
</body>
</html>
