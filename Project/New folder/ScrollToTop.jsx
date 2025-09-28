import React, { useState, useEffect } from "react";
import { Button } from "react-bootstrap";
import { BsArrowUpCircleFill } from "react-icons/bs";

const ScrollToTop = () => {
  const [visible, setVisible] = useState(false);


  const toggleVisible = () => {
    const scrolled = document.documentElement.scrollTop;
    if (scrolled > 200) {
      setVisible(true);
    } else {
      setVisible(false);
    }
  };


  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  };

  useEffect(() => {
    window.addEventListener("scroll", toggleVisible);
    return () => {
      window.removeEventListener("scroll", toggleVisible);
    };
  }, []);

  return (
    <div style={{ position: "fixed", bottom: "30px", right: "30px", zIndex: 1000 }}>
      {visible && (
        <Button
          variant="primary"
          onClick={scrollToTop}
          style={{ borderRadius: "50%", padding: "10px 12px" }}
        >
          <BsArrowUpCircleFill size={30} />
        </Button>
      )}
    </div>
  );
};

export default ScrollToTop;