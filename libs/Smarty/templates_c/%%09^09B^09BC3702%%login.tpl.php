<?php /* Smarty version 2.6.26, created on 2010-08-26 15:26:13
         compiled from body/login.tpl */ ?>
                <div class='main_search_block'>
                        <h1 class="imgBlock"><a href="http://www.pilsner-urquell.cz/cz/Pub-guide.html">Pilsner Urquell - Pub Guide<span class="imgSpan"></span></a></h1>
                </div>
                
                <div class="loginreg">

                  <div id="all">
                      <div id="content">
                          <div class="clearfix">
                              <div class="leftCol">
                                  <h2 class="prihlastese">Přihlašte se<span class="absbg"></span></h2>

                                  <form method='post' action="http://www.pilsner-urquell.cz/index.php?page=Klub-login-EXE" class="login"><fieldset>
                                                      <div class="rederror"><p class="higher">Chybně vyplněný formulář. Vyplňte prosím vyznačená pole.</p></div>
                                                                          <p class="text isError">
                                          <label for="log_mail">E-mail</label>
                                          <input type="text" id="log_mail" name="club_login_user_email" size="40" value="" />
                                      </p>
                                      <p class="text isError">
                                          <label for="log_heslo">Heslo</label>

                                          <input type="password" id="log_heslo" name="club_login_user_password" size="40" value="" />
                                      </p>
                                      <p class="labelleft">
                                          <input type="image" name="club_login_sbm_send" src="images/forms/prihlasit.png" width="97" height="31" title="Přihlásit" alt="Přihlásit" />
                                      </p>
                                      <p class="labelleft">
                                          <a href="#" onclick="javascript:showBoxSendForgetPassword();return false;">Zapomněli jste heslo?</a>
                                      </p>

                                      <div id="BoxSendForgetPassword" class="hiddenBoxSendForgetPassword" >

                                     <p class="labelleft smaller"><em>Zadejte prosím svůj e-mail nebo uživatelské jméno.</em></p>
                                     <p></p>
                                      <p class="text">
                                          <label for="send_mail_help">E-mail</label>
                                          <input type="text" id="send_mail" name="club_sendpass_user_email" size="40" value="" />
                                      </p>
                                      <p class="labelleft nebo">nebo</p>

                                       <p class="text uzivjmeno">
                                          <label for="send_pass_help">Uživatel. jméno</label>
                                          <input type="text" id="send_pass_help" name="club_sendpass_user_login" size="40" value="" />
                                      </p>
                            <p class="labelleft">
                                          <input type="image" name="club_sendpass_sbm_send" src="images/forms/loginreg-odeslat.png" width="97" height="31" title="Odeslat" alt="Odeslat" />
                                      </p>
                                    </div>
                          </fieldset></form>




                              </div>

                              <div class="rightCol">
                                  <h2 class="novaregistrace">Nová registrace<span class="absbg"></span></h2>
                                  <form action="http://www.pilsner-urquell.cz/index.php?page=Klub-registration-EXE" method='post' class="register"><fieldset>
                            <p><em>Všechny položky jsou povinné</em></p>
                                      <div class="datum">
                                          <label>Datum narození</label>
                                          <div class="dayy">
                                              <div>
                                  <select name="club_registration_user_birth_day">

                                    <option class="hider" value="">DD</option>
                                    <option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option>                </select>

                                </div>
                                          </div>
                                          <div class="monthy">
                                              <div>
                                  <select name="club_registration_user_birth_month">
                                    <option class="hider" value="">MM</option>
                                    <option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option>                </select>

                                </div>
                                          </div>
                                          <div class="yeary">
                                              <div>
                                  <select name="club_registration_user_birth_year">
                                    <option class="hider" value="">RRRR</option>
                                    <option value="1900" >1900</option><option value="1901" >1901</option><option value="1902" >1902</option><option value="1903" >1903</option><option value="1904" >1904</option><option value="1905" >1905</option><option value="1906" >1906</option><option value="1907" >1907</option><option value="1908" >1908</option><option value="1909" >1909</option><option value="1910" >1910</option><option value="1911" >1911</option><option value="1912" >1912</option><option value="1913" >1913</option><option value="1914" >1914</option><option value="1915" >1915</option><option value="1916" >1916</option><option value="1917" >1917</option><option value="1918" >1918</option><option value="1919" >1919</option><option value="1920" >1920</option><option value="1921" >1921</option><option value="1922" >1922</option><option value="1923" >1923</option><option value="1924" >1924</option><option value="1925" >1925</option><option value="1926" >1926</option><option value="1927" >1927</option><option value="1928" >1928</option><option value="1929" >1929</option><option value="1930" >1930</option><option value="1931" >1931</option><option value="1932" >1932</option><option value="1933" >1933</option><option value="1934" >1934</option><option value="1935" >1935</option><option value="1936" >1936</option><option value="1937" >1937</option><option value="1938" >1938</option><option value="1939" >1939</option><option value="1940" >1940</option><option value="1941" >1941</option><option value="1942" >1942</option><option value="1943" >1943</option><option value="1944" >1944</option><option value="1945" >1945</option><option value="1946" >1946</option><option value="1947" >1947</option><option value="1948" >1948</option><option value="1949" >1949</option><option value="1950" >1950</option><option value="1951" >1951</option><option value="1952" >1952</option><option value="1953" >1953</option><option value="1954" >1954</option><option value="1955" >1955</option><option value="1956" >1956</option><option value="1957" >1957</option><option value="1958" >1958</option><option value="1959" >1959</option><option value="1960" >1960</option><option value="1961" >1961</option><option value="1962" >1962</option><option value="1963" >1963</option><option value="1964" >1964</option><option value="1965" >1965</option><option value="1966" >1966</option><option value="1967" >1967</option><option value="1968" >1968</option><option value="1969" >1969</option><option value="1970" >1970</option><option value="1971" >1971</option><option value="1972" >1972</option><option value="1973" >1973</option><option value="1974" >1974</option><option value="1975" >1975</option><option value="1976" >1976</option><option value="1977" >1977</option><option value="1978" >1978</option><option value="1979" >1979</option><option value="1980" >1980</option><option value="1981" >1981</option><option value="1982" >1982</option><option value="1983" >1983</option><option value="1984" >1984</option><option value="1985" >1985</option><option value="1986" >1986</option><option value="1987" >1987</option><option value="1988" >1988</option><option value="1989" >1989</option><option value="1990" >1990</option><option value="1991" >1991</option><option value="1992" >1992</option><option value="1993" >1993</option><option value="1994" >1994</option><option value="1995" >1995</option><option value="1996" >1996</option><option value="1997" >1997</option><option value="1998" >1998</option><option value="1999" >1999</option><option value="2000" >2000</option><option value="2001" >2001</option><option value="2002" >2002</option><option value="2003" >2003</option><option value="2004" >2004</option><option value="2005" >2005</option><option value="2006" >2006</option><option value="2007" >2007</option><option value="2008" >2008</option><option value="2009" >2009</option>                </select>

                                </div>
                                          </div>
                                      </div>
                                      <p class="text">
                                          <label for="reg_psc">PSČ</label>
                                          <input type="text" id="reg_psc" name="club_registration_user_postcode" size="40" value="" />
                                      </p>
                                      <p class="text">
                                          <label for="reg_heslo">Heslo</label>
                                          <input type="password" id="reg_heslo" name="club_registration_user_password" size="40" value="" />
                                      </p>

                                      <p class="labelleft">
                                          <input type="image" name="club_login_sbm_send" src="images/forms/prihlasit.png" width="97" height="31" title="Přihlásit" alt="Přihlásit" />
                                      </p>

                                  </fieldset></form>
                              </div>
                          </div>
                      </div>

                      <div id="conttop"><span></span></div>
                      <div id="contbot"><span></span></div>
                      <div id="contline"></div>

                  </div>


                </div>