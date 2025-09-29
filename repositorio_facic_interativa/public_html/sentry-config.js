/**
 * SENTRY CONFIGURATION - FACIC INTERATIVA TESTE
 * Token: 093dfd16931b11f08ce1da19b018d4e2
 * Environment: Test
 * Project: INTERATIVA - FACIC
 */

import * as Sentry from "@sentry/browser";
import { Integrations } from "@sentry/tracing";

// Configuração do Sentry para FACIC INTERATIVA - AMBIENTE DE TESTE
Sentry.init({
  dsn: "https://093dfd16931b11f08ce1da19b018d4e2@o4504463825747968.ingest.sentry.io/4504463827058690",
  environment: "test",
  release: "facic-interativa@1.0.0-test",

  // Performance Monitoring - mais agressivo em teste
  tracesSampleRate: 1.0,

  // Session Replay - mais samples em teste
  replaysSessionSampleRate: 0.3,
  replaysOnErrorSampleRate: 1.0,

  integrations: [
    new Integrations.BrowserTracing({
      routingInstrumentation: Sentry.routingInstrumentation,
    }),
    new Sentry.Replay({
      sessionSampleRate: 0.3,
      errorSampleRate: 1.0,
    }),
  ],

  // Before send hook
  beforeSend(event) {
    // Em teste, log também no console
    console.log("[SENTRY TEST FACIC INTERATIVA]", event);

    // Remove sensitive data
    if (event.exception) {
      const error = event.exception.values[0];
      if (error && error.stacktrace && error.stacktrace.frames) {
        error.stacktrace.frames.forEach(frame => {
          if (frame.vars) {
            delete frame.vars.password;
            delete frame.vars.token;
            delete frame.vars.auth;
          }
        });
      }
    }
    return event;
  },

  // Additional context para teste
  initialScope: {
    tags: {
      institution: "FACIC",
      platform: "INTERATIVA",
      component: "repositorio",
      environment: "test"
    },
    user: {
      segment: "education_test"
    }
  }
});

export default Sentry;
export function captureCustomError(error, context = {}) {
  Sentry.withScope((scope) => {
    scope.setTag("error_type", "custom");
    scope.setTag("test_mode", true);
    scope.setContext("additional_info", context);
    Sentry.captureException(error);
  });
}

export function captureInfo(message, level = "info") {
  Sentry.captureMessage(`[TEST FACIC INTERATIVA] ${message}`, level);
}