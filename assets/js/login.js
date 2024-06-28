document
  .addEventListener('turbo:submit-end', (event) => {
    let response = event?.detail?.fetchResponse?.response;
    let status = response?.status;
    let url = response?.headers?.get('Location');
    let frame = response?.headers?.get('frame');

    if (status === 204 && url) {
      Turbo.visit(url, {action: 'advance', frame: frame})
      event.preventDefault();
      return false;
    }
  });
