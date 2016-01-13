<h3><?=$title?></h3>


<p><?=$user->presentation?></p>
    
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == 'admin'): ?>

    <hr>
    
    <h4>Administratörspanel</h4>
    <table class='admin'>
        <tr>
            <th>Användare</th>
            <th>RSS-flöden</th>
            <th>Databas</th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li><a href='<?=$this->url->create('users/add')?>'>Lägg till ny användare</a></li>
                    <li><a href='<?=$this->url->create('users/list')?>'>Visa alla användare</a></li>
                    <li><a href='<?=$this->url->create('users/active')?>'>Visa alla aktiva</a></li>
                    <li><a href='<?=$this->url->create('users/inactive')?>'>Visa alla inaktiva</a></li>
                    <li><a href='<?=$this->url->create('users/soft-deleted')?>'>Visa papperskorgen</a></li>
                </ul>
            </td>
            
            <td>
                <ul>
                    <li><a href='<?=$this->url->create('rss/setup')?>'>Skapa nytt RSS-flöde</a></li>
                    <li><a href='<?=$this->url->create('rss/list')?>'>Visa alla RSS-flöden</a></li>
                </ul>
            </td>
        </tr>
            
     </table>

<?php endif; ?>