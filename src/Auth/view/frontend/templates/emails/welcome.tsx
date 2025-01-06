import { Body, Button, Column, Container, Head, Heading, Hr, Html, Img, Link, Preview, Row, Section, Tailwind, Text } from "@react-email/components";
import * as React from "react";

const WelcomeEmail = () => {
  return (
    <Html>
      <Head />
      <Preview>Welcome to Our Platform!</Preview>
      <Body>
        <Tailwind>
          <Container style={{ backgroundColor: "#f7f7f7", padding: "20px" }}>
            <Row>
              <Column>
                <Heading style={{ fontSize: "24px", color: "#333" }}>Welcome to Our Platform, {`{customerName}`}!</Heading>
                <Text style={{ fontSize: "16px", color: "#555", marginBottom: "20px" }}>
                  We're excited to have you on board. Here at Our Platform, we offer a variety of tools to help you get started and make the most of
                  your experience. Let's take a look at what you can do.
                </Text>
                <Button
                  style={{
                    backgroundColor: "#007BFF",
                    color: "#fff",
                    borderRadius: "4px",
                    textDecoration: "none",
                    fontSize: "16px",
                  }}
                  className="bg-[#000000] rounded text-white text-[12px] font-semibold no-underline text-center px-5 py-3"
                  href="http://www.example.com/get-started"
                >
                  Get Started
                </Button>
                <Hr style={{ margin: "20px 0", borderColor: "#ddd" }} />
                <Section>
                  <Text style={{ fontSize: "14px", color: "#888" }}>
                    If you have any questions, feel free to reach out to our support team at{" "}
                    <Link href="mailto:support@example.com" style={{ color: "#007BFF" }}>
                      support@example.com
                    </Link>
                    .
                  </Text>
                </Section>
                <Section>
                  <Img
                    src="https://via.placeholder.com/600x200?text=Welcome+to+Our+Platform"
                    alt="Welcome"
                    width="100%"
                    style={{ borderRadius: "8px", marginTop: "20px" }}
                  />
                </Section>
              </Column>
            </Row>
          </Container>
        </Tailwind>
      </Body>
    </Html>
  );
};

export default WelcomeEmail;
