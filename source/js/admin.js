document.addEventListener("DOMContentLoaded", () => {
  const fetchIndicesBtn = document.getElementById("lisa-fetch-indices");
  const indicesList = document.getElementById("lisa-indices-list");

  const createParagraph = (text) => {
    const p = document.createElement("p");
    p.textContent = text;
    return p;
  };

  if (fetchIndicesBtn) {
    fetchIndicesBtn.addEventListener("click", async () => {
      fetchIndicesBtn.disabled = true;
      fetchIndicesBtn.textContent = lisa.fetch_status_label;

      const formData = new FormData();
      formData.append("action", "lisa_fetch_algolia_indices");
      formData.append("nonce", lisa.lisa_fetch_algolia_indices_nonce);

      try {
        const response = await fetch(lisa.ajax_url, {
          method: "POST",
          credentials: "same-origin",
          body: formData,
        });

        const result = await response.json();

        if (!result.success) {
          throw new Error();
        }

        if (result.data.length === 0) {
          indicesList.innerHTML = "";
          indicesList.appendChild(createParagraph(lisa.no_indices_label));
        } else {
          indicesList.innerHTML = "";
          const indicesString =
            result.data.length === 1
              ? lisa.found_one_index
              : lisa.found_many_indices.replace("%d", result.data.length);

          indicesList.appendChild(createParagraph(indicesString));
        }
      } catch (error) {
        indicesList.innerHTML = "";
        indicesList.appendChild(createParagraph(lisa.fetch_error_label));
      } finally {
        fetchIndicesBtn.disabled = false;
        fetchIndicesBtn.textContent = lisa.fetch_button_label;
      }
    });
  }
});
