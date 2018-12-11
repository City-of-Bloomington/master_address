CREATE OR REPLACE FUNCTION public.trig_set_geom() RETURNS trigger AS $$

DECLARE var_changed          boolean = false;
        var_loc_cols         text[];
        var_changed_loc_cols text[];
BEGIN

	-- get array of number columsn used for location
	var_loc_cols := ARRAY(
        SELECT column_name::text
        FROM information_schema.columns
        WHERE table_schema = TG_TABLE_SCHEMA
          AND table_name = TG_TABLE_NAME
          AND column_name::text IN('lon', 'lat', 'longitude','latitude','x_coord', 'y_coord', 'x_coordinate', 'y_coordinate')
    );

	IF TG_OP = 'UPDATE' THEN
		--only mark as changed if any of the location columns changed
		var_changed_loc_cols := ARRAY(
            SELECT n.key
            FROM       json_each_text(row_to_json(NEW)) AS n
            INNER JOIN json_each_text(row_to_json(OLD)) AS o ON (o.key = n.key AND n.key  = ANY(var_loc_cols))
            WHERE COALESCE(n.value,'') != COALESCE(o.value,'')
        );

	ELSIF  TG_OP ='INSERT' THEN
        -- for insert only do something if one of the location columns was inserted into
		var_changed_loc_cols =  ARRAY(
            SELECT n.key
            FROM json_each_text(row_to_json(NEW)) AS n
			WHERE n.key  = ANY(var_loc_cols)
			  AND n.value > ''
        );
	END IF;

	-- if any location columns changed, then we have an update
	var_changed := (array_upper(var_changed_loc_cols,1) > 0);

	IF var_changed THEN
		--if a changed column is a location column use that to set geometry
		 IF             '{x_coord,y_coord}'::text[] && var_changed_loc_cols THEN
			NEW.geom := ST_SetSRID(ST_Point(NEW.x_coord, NEW.y_coord), 2966);
		ELSIF '{x_coordinate,y_coordinate}'::text[] && var_changed_loc_cols THEN
			NEW.geom := ST_SetSRID(ST_Point(NEW.x_coordinate, NEW.y_coordinate),2966);

		ELSIF                   '{lon,lat}'::text[] && var_changed_loc_cols THEN
            NEW.geom = public.ST_Transform(public.ST_SetSRID(public.ST_Point(NEW.lon, NEW.lat),4326),2966);
		ELSIF        '{longitude,latitude}'::text[] && var_changed_loc_cols THEN
            NEW.geom = public.ST_Transform(public.ST_SetSRID(public.ST_Point(NEW.longitude, NEW.latitude),4326),2966);
		END IF;

		-- update secondary columns if they were not already updated
		IF NOT ( '{lon,lat}'::text[] && var_changed_loc_cols) AND '{lon,lat}'::text[] && var_loc_cols THEN
			SELECT ST_X(ngeom), ST_Y(ngeom) INTO NEW.lon, NEW.lat FROM (SELECT ST_Transform(NEW.geom, 4326) AS ngeom) As f;
		END IF;

		IF NOT ( '{longitude,latitude}'::text[] && var_changed_loc_cols) AND '{longitude,latitude}'::text[] && var_loc_cols THEN
			SELECT public.ST_X(ngeom), public.ST_Y(ngeom) INTO NEW.longitude, NEW.latitude FROM (SELECT public.ST_Transform(NEW.geom, 4326) AS ngeom) As f;
		END IF;

		IF NOT ( '{x_coord,y_coord}'::text[] && var_changed_loc_cols) AND '{x_coord,y_coord}'::text[] && var_loc_cols THEN
			SELECT ST_X(NEW.geom), ST_Y(NEW.geom) INTO NEW.x_coord, NEW.y_coord;
		END IF;

		IF NOT ( '{x_coordinate,y_coordinate}'::text[] && var_changed_loc_cols) AND '{x_coordinate,y_coordinate}'::text[] && var_loc_cols THEN
			SELECT ST_X(NEW.geom), ST_Y(NEW.geom) INTO NEW.x_coordinate, NEW.y_coordinate;
		END IF;
	END IF;
	RETURN NEW;
END;
$$ language plpgsql;
