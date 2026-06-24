-- Migration: extend `reviews` for the Airbnb-style review system.
--
-- Adds the six Airbnb category ratings (cleanliness, accuracy, communication,
-- check-in, location, value) alongside the existing overall `rating`, plus two
-- denormalized foreign keys (`property_id`, `host_id`) so the property page and
-- host rating can be aggregated from a single indexed table without joining
-- through bookings/properties on every read. `updated_at` tracks edits.
--
-- Safe to run on an existing database (e.g. the production VPS). Run with:
--   mysql -u root stayly < database/migrations/2026-06-24_extend_reviews.sql
--
-- The category columns get a transient DEFAULT 5 only so the ADD COLUMN does
-- not fail on any pre-existing rows; application inserts always set explicit
-- values. The defaults are dropped at the end so future inserts must be complete.

ALTER TABLE `reviews`
  MODIFY `rating` tinyint(4) NOT NULL,
  ADD COLUMN `property_id` int(11) NOT NULL DEFAULT 0 AFTER `booking_id`,
  ADD COLUMN `host_id` int(11) NOT NULL DEFAULT 0 AFTER `user_id`,
  ADD COLUMN `cleanliness`   tinyint(4) NOT NULL DEFAULT 5 AFTER `rating`,
  ADD COLUMN `accuracy`      tinyint(4) NOT NULL DEFAULT 5 AFTER `cleanliness`,
  ADD COLUMN `communication` tinyint(4) NOT NULL DEFAULT 5 AFTER `accuracy`,
  ADD COLUMN `checkin`       tinyint(4) NOT NULL DEFAULT 5 AFTER `communication`,
  ADD COLUMN `location`      tinyint(4) NOT NULL DEFAULT 5 AFTER `checkin`,
  ADD COLUMN `value`         tinyint(4) NOT NULL DEFAULT 5 AFTER `location`,
  ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `created_at`;

-- Backfill the denormalized keys for any reviews that already existed.
UPDATE `reviews` r
  JOIN `bookings` b   ON b.id = r.booking_id
  JOIN `properties` p ON p.id = b.property_id
SET r.property_id = p.id,
    r.host_id     = p.host_id
WHERE r.property_id = 0;

-- Indexes for the property-page and host-profile aggregate reads.
ALTER TABLE `reviews`
  ADD KEY `property_id` (`property_id`),
  ADD KEY `host_id` (`host_id`);

-- Foreign keys (mirrors the cascade behaviour of the existing constraints).
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_4` FOREIGN KEY (`host_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Drop the transient defaults so every future insert must supply all ratings.
ALTER TABLE `reviews`
  ALTER `property_id` DROP DEFAULT,
  ALTER `host_id` DROP DEFAULT,
  ALTER `cleanliness` DROP DEFAULT,
  ALTER `accuracy` DROP DEFAULT,
  ALTER `communication` DROP DEFAULT,
  ALTER `checkin` DROP DEFAULT,
  ALTER `location` DROP DEFAULT,
  ALTER `value` DROP DEFAULT;

-- Re-add the CHECK constraints that the column definitions in schema.sql carry.
-- (Wrapped so the migration is still useful on MariaDB/MySQL builds that
--  silently ignore CHECKs.)
ALTER TABLE `reviews`
  ADD CONSTRAINT `chk_rating`        CHECK (`rating` between 1 and 5),
  ADD CONSTRAINT `chk_cleanliness`   CHECK (`cleanliness` between 1 and 5),
  ADD CONSTRAINT `chk_accuracy`      CHECK (`accuracy` between 1 and 5),
  ADD CONSTRAINT `chk_communication` CHECK (`communication` between 1 and 5),
  ADD CONSTRAINT `chk_checkin`       CHECK (`checkin` between 1 and 5),
  ADD CONSTRAINT `chk_location`      CHECK (`location` between 1 and 5),
  ADD CONSTRAINT `chk_value`         CHECK (`value` between 1 and 5);
