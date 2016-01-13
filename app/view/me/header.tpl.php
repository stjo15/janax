<span class='loginlink'><?=isset($loginLink) ? $loginLink : null?></span>
<img class='sitelogo' src='<?=$this->url->asset("img/logo.png")?>' alt='Janax logo'/>
<a href='<?=$this->url->create('')?>'><span class='sitetitle'><?=isset($siteTitle) ? $siteTitle : "Janax Forum Framework"?></span></a>
<span class='siteslogan'><?=isset($siteTagline) ? $siteTagline : "Share knowledge, get answers!"?></span>
<a href="#" class="scrolltotop" title='Scroll to top'><i class="fa fa-chevron-up fa-2x theme"></i></a>