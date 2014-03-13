    <h1>Pattern Library</h1>
    <p class="lede">Common snippets</p>

    <section>
      <h2>Feedback</h2>
      <div class="feedback">General feedback.</div>
      <div class="feedback success">Success feedback.</div>
      <div class="feedback error">Error feedback.</div>
    </section>

    <section>
      <h2>Form</h2>
      <form action="" method="post">
        <fieldset>
          <legend>Legend</legend>
          <p class="caption small">Required fields are marked <span class="required">*</span>.</p>
          <label for="text">
            Text input <span class="required">*</span>
            <input type="text" id="text" name="text">
          </label>
          <label for="text-disabled">
            Disabled text input
            <input type="text" id="text-disabled" name="text-disabled" disabled>
          </label>
          <div class="group--inline">
            <input type="checkbox" id="checkbox" name="checkbox">
            <label for="checkbox">Checkbox input</label>
            <input type="checkbox" id="checkbox-disabled" name="checkbox-disabled" disabled>
            <label for="checkbox-disabled">Disabled checkbox input</label>
          </div>
          <div class="group--inline">
            <input type="radio" id="radio" name="radio">
            <label for="radio">Radio input</label>
            <input type="radio" id="radio-disabled" name="radio-disabled" disabled>
            <label for="radio-disabled">Disabled radio input</label>
          </div>
          <label for="textarea">
            Textarea
            <textarea name="textarea" id="textarea"></textarea>
          </label>
        </fieldset>
        <div class="commands">
          <button class="button" type="submit">Post</button>
          <button class="button" type="submit" disabled>Disabled</button>
        </div>
      </form>
    </section>

    <section>
      <h2>List without marks</h2>
      <ul class="no-markers">
        <li>List item 1</li>
        <li>List item 2</li>
        <li>List item 3</li>
      </ul>
    </section>

    <section>
      <h2>Meta informations</h2>
      <h3>Date</h3>
      <p class="meta-date"><span class="day">20</span><span class="month">nov</span></p>
    </section>

    <section>
      <h2>Navigation</h2>
      <ul class="nav spaced-links no-markers" role="navigation">
        <li><a href="">Item 1</a></li>
        <li><a href="">Item 2</a></li>
        <li><a href="">Item 3</a></li>
        <li><a href="">Item 4</a></li>
      </ul>
    </section>