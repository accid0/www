<div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->Имя]; ?></label>
      <div class="mws-form-item large">
        <input rel='1' type="text" name="Имя" class="mws-textinput required" />
      </div>
    </div>
    <div class="mws-form-row">
      <label>WYSIWYG</label>
      <div class="mws-form-item">
        <textarea class="elrte" name='elrte' cols="auto" rows="auto"></textarea>
      </div>
    </div>
    <div class="mws-form-row">
    <label>Email Validation</label>
    <div class="mws-form-item large">
      <input type="text" name="emailField" class="mws-textinput required email" />
    </div>
    </div>
    <div class="mws-form-row">
      <label>URL Validation</label>
      <div class="mws-form-item large">
        <input type="text" name="urlField" class="mws-textinput required url" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->НН]; ?></label>
      <div class="mws-form-item large">
        <input rel='0' type="text" name="НН" class="mws-textinput required digits" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->Роли]; ?></label>
      <div class="mws-form-item small">
        <select rel='2' name= 'Роли[]' multiple="multiple" size="2" class="chzn-select required">
          <option>Администраторы</option>
          <option>Гости</option>
        </select>
        <label for="Роли" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>File Input Validation</label>
      <div class="mws-form-item large">
        <input type="file" name="picture" class="required" />
        <label for="picture" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>Spinner Validation</label>
      <div class="mws-form-item medium">
        <input type="text" id="s4" name="spinner" class="mws-textinput required" value="10" />
        <label for="s4" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>Radiobutton Validation</label>
      <div class="mws-form-item large">
        <ul class="mws-form-list">
          <li><input id="gender_male" type="radio" name="gender" class="required" /> <label for="gender_male">Male</label></li>
          <li><input id="gender_female" type="radio" name="gender" /> <label for="gender_female">Female</label></li>
        </ul>
        <label for="gender" class="error plain" generated="true" style="display:none"></label>
      </div>
    </div>