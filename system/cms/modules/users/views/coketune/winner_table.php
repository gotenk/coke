					
						<table>
							<tbody>
								<tr>
									<th>NO.</th>
									<th>NAMA</th>
								</tr>
								
								<?php
									$num = $offset; 
									foreach($winners as $winner): $num++;?>
								<tr>
									<td><?php echo $num;?></td>
									<td><?php echo $winner->name;?></td>
								</tr>			
								<?php endforeach;?>	

							</tbody>
						</table>
						<div class="pagination" id="pagination-pemenang" data-offset="<?php echo $offset?>" data-is-next="<?php echo $is_next?>">
							<a href="" id="prev" class="action prev <?php echo ($offset==0) ? '' : 'more';?>"><i class="icon chevron prev"></i>sebelumnya</a>						
							<a href="" id="next" class="action next <?php echo ($is_next!=0) ? 'more' : '';?>">berikutnya<i class="icon chevron next"></i></a>
						</div> <!-- .pagination -->