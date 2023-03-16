<script>
  const runQuery = (e) => {
    e.preventDefault();
    const inputs = document.querySelectorAll(".keyword-form input");
    const valOne = inputs[0].value;
    const valTwo = inputs[1].value;
    window.location.href = `?keywords=1&keywordOne=${valOne}&keywordTwo=${valTwo}`;
  }

  document.querySelector(".keyword-form button").addEventListener("click", runQuery);
</script>
</body>

</html>