<div class="corp">
	<h1>{registry}</h1>
	<form action="{link}inscription" method="post" id="myForm" onsubmit="return verifForm(this)">
		<table>
			<tr>
				<td colspan="2" class="c"><b>{form}</b></td>
			</tr>
			<tr>
				<td>{GameName}</td>
				<td>
					<input id="login" name="character" size="20" maxlength="20" type="text" onblur="verifPseudo(this)"/>
					<span class="tooltip">{error_pseudo}</span>
				</td>
			</tr>
			<tr>
				<td>{neededpass}</td>
				<td>
					<input id="password" name="passwrd" size="20" maxlength="20" type="password" onblur="verifPass(this)"/>
					<span class="tooltip">{error_motdepasse}</span>
				</td>
			</tr>
			<tr>
				<td>{E-Mail}</td>
				<td>
				<input id="email" name="email" size="20" maxlength="40" type="text" onblur="verifMail(this)"/>
					<span class="tooltip">{error_email}</span>
				</td>
			</tr>
			<tr>
			  <td>{MainPlanet}</td>
			  <td><input name="planet" size="20" maxlength="20" type="text" /></td>
			</tr>
			<tr>
			  <td>{Sex}</td>
			  <td><select name="sex" id="sex" onblur="verifSex(this)">
					<option value="*">{Undefined}</option>
					<option value="M">{Male}</option>
					<option value="F">{Female}</option>
					</select>
				<span class="tooltip">{error_sel_sexe}</span>
				</td>
			</tr>
			<tr>
				<td><img alt="image de protection" src="{secu}"/></td>
				<td><input type="text" name="verif" value=""></td>
			</tr>
			<tr>
			{code_secu}
			<td>{affiche}</td>
			</tr>
			<tr>
			  <td><input id="rgt" name="rgt" type="checkbox" />
				{accept}</td>
			  <td><input id="submit" name="submit" type="submit" value="{signup}" /></td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript" src="Vues/scripts/formulaire.js"></script>