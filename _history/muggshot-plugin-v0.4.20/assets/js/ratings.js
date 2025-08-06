
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.stars').forEach(function (starContainer) {
    const postId = starContainer.dataset.postId;
    const savedRating = localStorage.getItem('muggshot_rating_' + postId);
    const savedDate = localStorage.getItem('muggshot_rating_date_' + postId);

    function canVoteAgain() {
      if (!savedDate) return true;
      const then = new Date(savedDate);
      const now = new Date();
      const diffDays = Math.floor((now - then) / (1000 * 60 * 60 * 24));
      return diffDays <= 7;
    }

    if (savedRating) {
      starContainer.querySelectorAll('.star').forEach((star, index) => {
        if (index < parseInt(savedRating)) {
          star.classList.add('selected');
        }
      });
      const msg = document.createElement('div');
      msg.className = 'rating-message';
      msg.textContent = 'Du har röstat: ' + savedRating + ' av 4';
      starContainer.appendChild(msg);
    }

    if (canVoteAgain()) {
      const stars = starContainer.querySelectorAll('.star');
      stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
          stars.forEach((s, i) => {
            s.classList.toggle('hovered', i <= index);
          });
        });
        star.addEventListener('mouseout', () => {
          stars.forEach(s => s.classList.remove('hovered'));
        });
        star.addEventListener('click', () => {
          localStorage.setItem('muggshot_rating_' + postId, index + 1);
          localStorage.setItem('muggshot_rating_date_' + postId, new Date().toISOString());
          stars.forEach((s, i) => s.classList.toggle('selected', i <= index));
          const msg = starContainer.querySelector('.rating-message') || document.createElement('div');
          msg.className = 'rating-message';
          msg.textContent = 'Du har röstat: ' + (index + 1) + ' av 4';
          starContainer.appendChild(msg);
        });
      });
    }
  });
});
