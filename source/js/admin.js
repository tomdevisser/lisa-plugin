document.addEventListener("DOMContentLoaded", () => {
  const fetchIndicesBtn = document.getElementById("lisa-fetch-indices");
  const fetchIndexSettingsBtn = document.getElementById(
    "lisa-fetch-index-settings"
  );
  const indicesList = document.getElementById("lisa-indices-list");

  const createParagraph = (text) => {
    const p = document.createElement("p");
    p.textContent = text;
    return p;
  };

  if (fetchIndexSettingsBtn) {
    const paginationHitsPerPage = document.getElementById(
      "lisa_algolia_pagination_hits_per_page"
    );
    const paginationPaginationLimitedTo = document.getElementById(
      "lisa_algolia_pagination_pagination_limited_to"
    );

    fetchIndexSettingsBtn.addEventListener("click", async () => {
      fetchIndexSettingsBtn.disabled = true;
      fetchIndexSettingsBtn.textContent = lisa.fetch_status_label;

      const formData = new FormData();
      formData.append("action", "lisa_fetch_algolia_index_settings");
      formData.append("nonce", lisa.lisa_fetch_algolia_index_settings_nonce);
      formData.append("index_name", fetchIndexSettingsBtn.dataset.indexName);

      try {
        const response = await fetch(lisa.ajax_url, {
          method: "POST",
          credentials: "same-origin",
          body: formData,
        });

        const result = await response.json();

        console.log(result);

        if (!result.success) {
          throw new Error();
        }

        paginationHitsPerPage.value = result.data.hitsPerPage;
        paginationPaginationLimitedTo.value = result.data.paginationLimitedTo;
      } catch (error) {
        console.log(error);
      } finally {
        fetchIndexSettingsBtn.disabled = false;
        fetchIndexSettingsBtn.textContent =
          lisa.fetch_index_settings_button_label;
      }
    });
  }

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
        fetchIndicesBtn.textContent = lisa.fetch_indices_button_label;
      }
    });
  }
});
